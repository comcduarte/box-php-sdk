<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\FieldType;

class Field
{
    /**
     * The unique ID of the metadata template field.
     * 
     * @var string
     */
    public string $id;
    
    /**
     * The type of field. The basic fields are a string field for text, a float field for numbers, and a date fields to present 
     * the user with a date-time picker. Additionally, metadata templates support an enum field for a basic list of items, 
     * and multiSelect field for a similar list of items where the user can select more than one value.
     * Value is one of string,float,date,enum,multiSelect
     * 
     * @var FieldType
     */
    public FieldType $type;
    
    /**
     * A description of the field. This is not shown to the user.
     * 
     * @var string
     */
    public string $description;
    
    /**
     * The display name of the field as it is shown to the user in the web and mobile apps.
     *
     * @var string
     */
    public string $displayName;
    
    /**
     * Whether this field is hidden in the UI for the user and can only be set through the API instead.
     * 
     * @var boolean
     */
    public bool $hidden;
    
    /**
     * A unique identifier for the field. The identifier must be unique within the template to which it belongs.
     * 
     * @var string
     */
    public string $key;
    
    /**
     * 
     * @var Options
     */
    public Options $options;
}