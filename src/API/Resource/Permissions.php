<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource;

class Permissions
{
    use HydrationTrait;
    
    public bool $can_download = true;
    
    public bool $can_preview = true;
    
    public bool $can_edit = false;
    
    public bool $can_delete = false;
    
    public bool $can_rename = false;
    
    public bool $can_upload = false;
    
    public bool $can_apply_watermark = false;
    
    public bool $can_invite_collaborator = false;
}