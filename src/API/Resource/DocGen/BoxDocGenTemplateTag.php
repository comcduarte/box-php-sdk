<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource\DocGen;

use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Enum\BoxDocGenTemplateTagType;

class BoxDocGenTemplateTag extends AbstractResource
{
    public array $json_paths;
    
    public string $tag_content;
    
    public BoxDocGenTemplateTagType $tag_type;
}