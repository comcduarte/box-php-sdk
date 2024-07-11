<?php
declare(strict_types=1);

namespace Laminas\Box\API\Resource;

class OAuth20Error
{
    use HydrationTrait;
    
    /**
     * The type of the error returned.
     * @var string
     */
    public $error;
    
    /**
     * The type of the error returned.
     * @var string
     */
    public $error_description;
}