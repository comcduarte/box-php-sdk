<?php
namespace Laminas\Box\API\Resource;

class NotificationEmail
{
    /**
     * The email address to send the notifications to.
     * @var string
     */
    public $email;
    
    /**
     * Specifies if this email address has been confirmed.
     * @var boolean
     */
    public $is_confirmed;
}