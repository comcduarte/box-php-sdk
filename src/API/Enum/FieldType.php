<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Enum;

enum FieldType: string
{
    case Text = 'string';
    case Number = 'float';
    case Date = 'date';
    case Enum = 'enum';
    case MultiSelect = 'multiselect';
}