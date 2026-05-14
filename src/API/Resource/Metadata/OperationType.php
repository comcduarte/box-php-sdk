<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource\Metadata;

enum OperationType: string
{
    case Add        = "add";
    case Replace    = "replace";
    case Remove     = "remove";
    case Test       = "test";
    case Move       = "move";
    case Copy       = "copy";
}