<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\BoxSign;

use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Enum\ResourceType;

class BoxSignTemplate extends AbstractResource
{

    public ResourceType $type = ResourceType::Sign_Template;

    public $additional_info;

    public bool $are_email_settings_locked;

    public bool $are_fields_locked;

    public bool $are_files_locked;

    public bool $are_options_locked;

    public bool $are_recipients_locked;

    public $custom_branding;

    public int $days_valid;

    public string $email_message;

    public string $email_subject;

    public string $name;

    public Folder $parent_folder;

    public $ready_sign_link;

    public array $signers = [];

    public array $source_files = [];
    
    /**
     * Gets Box Sign templates created by a user.
     */
    public function list_box_sign_templates()
    {
        $endpoint = 'https://api.box.com/2.0/sign_templates';
        
        $params = [
            //-- No Parameters Required --//
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a collection of templates.
                 */
                $templates = new BoxSignTemplates();
                $templates->hydrate($this->response);
                return $templates;
            case 401:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
}