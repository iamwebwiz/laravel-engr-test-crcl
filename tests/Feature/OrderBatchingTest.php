<?php

namespace Tests\Feature;

use App\Models\Hmo;
use App\Modules\Batching\Enums\BatchingStrategyEnum;
use App\Modules\Batching\Notifications\BatchOrderCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderBatchingTest extends TestCase
{
    use RefreshDatabase;

    private array $orderData = [
        'provider_name' => 'We Care Hospital',
        'encounter_date' => '2024-08-21 11:00:00',
        'items' => [
            [
                'name' => 'Hepatitis A Test',
                'quantity' => 1,
                'unit_price' => 25.50,
            ],
            [
                'name' => 'Pregnancy Test',
                'quantity' => 1,
                'unit_price' => 20.00,
            ],
        ],
    ];

    public function testItReturnsErrorsWhenNoDataIsProvided()
    {
        $response = $this->post(route('batch-order'), []);

        $response->assertSessionHasErrors(['provider_name', 'encounter_date', 'hmo_code', 'items']);
    }

    public function testItReturnsErrorsWhenInvalidDataIsProvided()
    {
        $response = $this->post(route('batch-order'), [
            'provider_name' => 'Some provider',
            'hmo_code' => 'invalid_hmo_code',
            'encounter_date' => 'invalid_encounter_date',
            'items' => [
                'name' => 'An order item',
                'quantity' => 0,
                'unit_price' => -1,
            ]
        ]);

        $response->assertSessionHasErrors(['encounter_date', 'hmo_code', 'items.*.quantity', 'items.*.unit_price']);
    }

    public function testOrderBatchingSuccessfulWithEncounterDateStrategy()
    {
        Notification::fake();

        $this->seed();

        $response = $this->post(route('batch-order'), [
            'hmo_code' => 'HMO-C',
            ...$this->orderData,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('batches', 1);
        $this->assertDatabaseHas('batches', [
            'name' => "{$this->orderData['provider_name']} Aug 2024",
            'strategy' => BatchingStrategyEnum::ENCOUNTER_DATE->value,
        ]);

        $hmo = Hmo::firstWhere('code', 'HMO-C');

        Notification::assertCount(1);
        Notification::assertSentTo($hmo, BatchOrderCreatedNotification::class);
    }

    public function testOrderBatchingSuccessfulWithSubmissionDateStrategy()
    {
        Notification::fake();

        $this->seed();

        $response = $this->post(route('batch-order'), [
            'hmo_code' => 'HMO-A',
            ...$this->orderData,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('batches', 1);
        $this->assertDatabaseHas('batches', [
            'name' => "{$this->orderData['provider_name']} Aug 2024",
            'strategy' => BatchingStrategyEnum::SUBMISSION_DATE->value,
        ]);

        $hmo = Hmo::firstWhere('code', 'HMO-A');

        Notification::assertCount(1);
        Notification::assertSentTo($hmo, BatchOrderCreatedNotification::class);
    }

    public function testOrderBatchingSuccessfulWithNoBatchingStrategy()
    {
        Notification::fake();

        $this->seed();

        $response = $this->post(route('batch-order'), [
            'hmo_code' => 'HMO-D',
            ...$this->orderData,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseCount('batches', 1);
        $this->assertDatabaseHas('batches', [
            'strategy' => BatchingStrategyEnum::ENCOUNTER_DATE->value,
        ]);

        $hmo = Hmo::firstWhere('code', 'HMO-D');

        Notification::assertCount(1);
        Notification::assertSentTo($hmo, BatchOrderCreatedNotification::class);
    }
}
