<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\BoxSign;

use comcduarte\Box\API\Enum\ResourceType;
use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Resource\File;
use comcduarte\Box\API\Resource\Files;
use comcduarte\Box\API\Resource\Folder;
use comcduarte\Box\API\Resource\ClientError;

class BoxSignRequest extends AbstractResource
{

    public ResourceType $type = ResourceType::Sign_Request;

    public bool $are_reminder_enabled;

    public bool $ar_signatures_enabled;

    public string $auto_expire_at;

    public string $collaborator_level;

    public int $days_valid;

    public string $declined_redirect_url;

    public string $email_message;

    public string $email_subject;

    public string $external_id;

    public string $external_system_name;

    public bool $is_document_preparation_needed;

    public string $name;

    public Folder $parent_folder;

    public $prefill_tags;

    public string $prepare_url;

    public string $redirect_url;

    public string $sender_email;

    public int $sender_id;

    public BoxSignFiles $sign_files;

    public string $signature_color;

    public array $signers = [];

    public File $signing_log;

    public Files $source_files;

    public BoxSignStatusType $status;

    public string $template_id;
    
    public function create_box_sign_request(Folder $parent_folder, array $signers, array $source_files): self|ClientError
    {
        if (!isset($parent_folder) || !isset($signers) || !isset($source_files)) {
            return $this->error();
        }
        
        $endpoint = 'https://api.box.com/2.0/sign_requests';
        
        $params = [
            //-- No Parameters Required --//
        ];
        
        $data = [
            'signers' => $signers,
            'source_files' => $source_files,
            'parent_folder' => $parent_folder,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                /**
                 * Returns a Box Sign request object.
                 */
                $this->hydrate($this->response);
                return $this;
            default:
                /**
                 * An unexpected client error
                 */
                return $this->error();
        }
    }
    
    public function cancel_box_sign_request()
    {
        
    }
    
    public function resend_box_sign_request()
    {
        
    }
}