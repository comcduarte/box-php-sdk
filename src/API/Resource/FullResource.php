<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

class FullResource extends BaseResource
{
    public string $created_at;
    public BaseResource $created_by;
    public BaseResource $enterprise;
    
}