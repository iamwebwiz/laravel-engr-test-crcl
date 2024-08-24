<?php

namespace App\Modules\Batching\Contracts;

use App\Models\Batch;
use App\Models\Order;

interface BatchingStrategyContract
{
    /**
     * Get the batch of an order
     *
     * @param Order $order
     * @return Batch
     */
    public function getBatch(Order $order): Batch;
}
