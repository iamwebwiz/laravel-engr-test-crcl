<?php

namespace App\Modules\Batching\Services;

use App\Models\Order;
use App\Modules\Batching\Enums\BatchingStrategyEnum;
use App\Modules\Batching\Exceptions\HmoNotSetException;
use App\Modules\Batching\Notifications\BatchOrderCreatedNotification;
use App\Modules\Batching\Strategies\EncounterDateStrategy;
use App\Modules\Batching\Strategies\SubmissionDateStrategy;
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
            'hmo_code' => ['required', 'string', 'exists:hmo,hmo_code'],
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
            $strategy = self::DEFAULT_STRATEGY;
        }

        $batch = app($strategy)->getBatch($order);

        $order->batch()->associate($batch);
        $order->save();

        $orderHmo->notify(new BatchOrderCreatedNotification($order));
    }
}
