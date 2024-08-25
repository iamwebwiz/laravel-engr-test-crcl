<?php

namespace App\Modules\Batching\Services;

use App\Models\Hmo;
use App\Models\Order;
use App\Modules\Batching\Enums\BatchingStrategyEnum;
use App\Modules\Batching\Exceptions\BatchingException;
use App\Modules\Batching\Exceptions\HmoNotSetException;
use App\Modules\Batching\Notifications\BatchOrderCreatedNotification;
use App\Modules\Batching\Strategies\EncounterDateStrategy;
use App\Modules\Batching\Strategies\SubmissionDateStrategy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class BatchingService
{
    use AsAction;

    private const DEFAULT_STRATEGY = BatchingStrategyEnum::ENCOUNTER_DATE->value;

    private array $strategies = [
        'encounter_date' => EncounterDateStrategy::class,
        'submission_date' => SubmissionDateStrategy::class,
    ];

    public function rules(): array
    {
        return [
            'provider_name' => ['required', 'string'],
            'hmo_code' => ['required', 'string', 'exists:hmos,code'],
            'encounter_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function handle(Order $order): void
    {
        $orderHmo = $order->hmo;

        if (! $orderHmo) {
            throw new HmoNotSetException('[Bad Order] - An HMO has not been set for this order.');
        }

        if ($orderHmo->batching_strategy && ! empty($this->strategies[$orderHmo->batching_strategy])) {
            $strategy = $this->strategies[$orderHmo->batching_strategy];
        } else {
            $strategy = $this->strategies[self::DEFAULT_STRATEGY];
        }

        $batch = app($strategy)->getBatch($order);

        if (! $batch) {
            throw new BatchingException('Failed to find or create a batch for this order.');
        }

        $order->batch()->associate($batch);
        $order->save();

        $orderHmo->notify(new BatchOrderCreatedNotification($order));
    }

    public function asController(ActionRequest $request)
    {
        $payload = $request->validated();

        $hmo = Hmo::query()->where('code', $payload['hmo_code'])->first();

        DB::beginTransaction();

        try {
            $total = 0; // the total order amount

            foreach ($payload['items'] as &$item) {
                $item['sub_total'] = $item['quantity'] * $item['unit_price'];
                $total += $item['sub_total'];
            }

            $payload['hmo_id'] = $hmo->id;
            $payload['total'] = $total;

            $order = Order::create($payload);

            $order->items()->createMany($payload['items']);

            $this->handle($order); // handle the batching of the order

            DB::commit();

            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Order has been submitted successfully.',
            ]);

            return back();
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error($exception);

            if ($exception instanceof HmoNotSetException || $exception instanceof BatchingException) {
                $message = $exception->getMessage();
            } else {
                $message = 'An error occurred while trying to create a batch for this order. Please try again later or contact the administrator.';
            }

            session()->flash('alert', [
                'type' => 'error',
                'message' => $message,
            ]);

            return back()->withErrors(['error' => $message]);
        }
    }
}
