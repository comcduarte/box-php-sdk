<?php

declare(strict_types=1);

namespace comcduarte\Box\API\Enum;

Enum ResourceType: string
{
    case File = 'file';
    case Folder = 'folder';
    case File_Version = 'file_version';
    case Workflow = 'workflow';
    case Comment = 'comment';
    case Metadata_Template = 'metadata_template';
    case User = 'user';
}