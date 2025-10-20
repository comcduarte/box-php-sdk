<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

class FileVersion extends AbstractResource
{
    public string $id;
    
    public string $type = 'file_version';
    
    public string $created_at;
    
    public string $modified_at;
    
    public User $modified_by;
    
    public string $name;
    
    public string $purged_at;
    
    public string $restored_at;
    
    public User $restored_by;
    
    public string $sha1;
    
    public int $size;
    
    public string $trashed_at;
    
    public User $trashed_by;
    
    public string $uploaded_display_name;
    
    public string $version_number;
}