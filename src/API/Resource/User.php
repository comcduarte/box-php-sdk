<?php
namespace Laminas\Box\API\Resource;

class User
{
    public $id;
    public $type;
    public $address;
    public $avatar_url;
    public $created_at;
    public $job_title;
    public $language;
    public $login;
    public $max_upload_size;
    public $modified_at;
    public $name;
    
    /**
     * 
     * @var NotificationEmail
     */
    public $notification_email;
    public $phone;
    public $space_amount;
    public $space_used;
    public $status;
    public $timezone;
}