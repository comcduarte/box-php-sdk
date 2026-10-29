<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Enum;

enum BoxDocGenTemplateTagType: string
{

    case TEXT = 'text';

    case ARITHMETIC = 'arithmetic';

    case CONDITIONAL = 'conditional';

    case FOR_LOOP = 'for-loop';

    case TABLE_LOOP = 'table-loop';

    case IMAGE = 'image';
}