<?php

namespace App\Modules\Batching\Strategies;

use App\Models\Batch;
use App\Models\Order;
use App\Modules\Batching\Contracts\BatchingStrategyContract;
use App\Modules\Batching\Enums\BatchingStrategyEnum;

class EncounterDateStrategy extends BatchingStrategyContract
{
    public function getBatch(Order $order): Batch
    {
        $batchDate = $this->getBatchDate($order);
        $batchName = $this->getBatchName($order, $batchDate);

        return $this->getBatchRecord($order, $batchName, BatchingStrategyEnum::ENCOUNTER_DATE);
    }

    public function getBatchDate(Order $order)
    {
        return $order->encounter_date->format('M Y');
    }
}
