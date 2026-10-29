<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Enum;

Enum ConflictResolutionType: string
{
    
    case None = 'none';
    
    case Overwrite = 'overwrite';
}