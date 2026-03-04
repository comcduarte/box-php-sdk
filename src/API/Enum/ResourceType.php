<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Enum;

Enum ResourceType: string
{

    case DocGen_Batch = 'docgen_batch';
    
    case File = 'file';

    case Folder = 'folder';

    case File_Version = 'file_version';

    case Workflow = 'workflow';

    case Comment = 'comment';
    
    case Metadata_Cascade_Policy = 'metadata_cascade_policy';

    case Metadata_Instance = 'metadata_instance';

    case Metadata_Template = 'metadata_template';

    case User = 'user';

    case Sign_Request = 'sign-request';

    case Sign_Template = 'sign-template';
}