<?php
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\ResourceType;

class ClassificationTemplate
{
    public string $type = ResourceType::Metadata_Template;
    public bool $copyInstanceOnItemCopy;
    public string $displayName = 'Classification';
    public $fields;
    public bool $hidden;
    public string $scope;
    public string $templateKey = 'securityClassification-6VMVochwUWo';
}