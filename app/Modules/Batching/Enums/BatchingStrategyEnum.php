<?php

namespace App\Modules\Batching\Enums;

enum BatchingStrategyEnum: string
{
    case ENCOUNTER_DATE = 'encounter_date';

    case SUBMISSION_DATE = 'submission_date';
}
