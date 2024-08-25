<?php

namespace App\Modules\Batching\Contracts;

use App\Models\Batch;
use App\Models\Order;
use App\Modules\Batching\Enums\BatchingStrategyEnum;

abstract class BatchingStrategyContract
{
    abstract public function getBatch(Order $order): Batch;

    abstract public function getBatchDate(Order $order);

    public function getBatchName(Order $order, string $batchDate): string
    {
        return ucwords("{$order->provider_name} {$batchDate}");
    }

    public function getBatchRecord(Order $order, string $batchName, BatchingStrategyEnum $strategy): Batch
    {
        return Batch::firstOrCreate(
            ['name' => $batchName, 'hmo_id' => $order->hmo_id],
            ['strategy' => $strategy->value]
        );
    }
}
