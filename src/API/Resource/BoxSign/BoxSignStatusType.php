<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\BoxSign;

Enum BoxSignStatusType: string
{

    case CONVERTING = 'converting';

    case CREATED = 'created';

    case SENT = 'sent';

    case VIEWED = 'viewed';

    case SIGNED = 'signed';

    case CANCELLED = 'cancelled';

    case DECLINED = 'declined';

    case ERROR_CONVERTING = 'error_converting';

    case ERROR_SENDING = 'error_sending';

    case EXPIRED = 'expired';

    case FINALIZING = 'finalizing';

    case ERROR_FINALIZING = 'error_finalizing';
}