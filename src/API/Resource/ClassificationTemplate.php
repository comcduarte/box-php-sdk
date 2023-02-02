<?php
namespace Laminas\Box\API\Resource;

class ClassificationTemplate
{
    public $id;
    public $type;
    public $copyInstanceOnItemCopy;
    public $displayName;
    
    /**
     * 
     * @var \Laminas\Box\API\Resource\Field[]
     */
    public $fields;
}