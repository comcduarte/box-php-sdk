<?php
namespace comcduarte\Box\API\Resource;

class ClassificationTemplate
{
    public $id;
    public $type;
    public $copyInstanceOnItemCopy;
    public $displayName;
    
    /**
     * 
     * @var \comcduarte\Box\API\Resource\Field[]
     */
    public $fields;
}