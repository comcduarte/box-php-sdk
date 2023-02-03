<?php
namespace Laminas\Box\API\Resource;

class ClientError
{
    /**
     * Value is always error.
     * @var string
     */
    public $type = 'error';
    
    /**
     * A Box-specific error code
     * Value is one of created,accepted,no_content,redirect,not_modified,bad_request,unauthorized,forbidden,not_found,method_not_allowed,conflict,precondition_failed,too_many_requests,internal_server_error,unavailable,item_name_invalid,insufficient_scope
     * @var string
     */
    public $code;
    
    /**
     * A free-form object that contains additional context about the error. The possible fields are defined on a per-endpoint basis. message is only one example.
     */
    public $context_info;
    
    /**
     * A URL that links to more information about why this error occurred.
     * @var string
     */
    public $help_url;
    
    /**
     * A short message describing the error.
     * @var string
     */
    public $message;
    
    /**
     * A unique identifier for this response, which can be used when contacting Box support.
     * @var string
     */
    public $request_id;
    
    /**
     * The HTTP status of the response.
     * @var integer
     */
    public $status;
}