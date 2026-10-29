<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Enum;

enum Access: string
{
    case Open = 'open';
    case Company = 'compay';
    case Collaborators = 'collaborators';
}