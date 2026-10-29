<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Enum;

Enum StatusType: string
{

    case SUBMITTED = 'submitted';

    case COMPLETED = 'completed';

    case FAILED = 'failed';

    case COMPLETED_WITH_ERROR = 'completed_with_error';

    case PENDING = 'pending';
}