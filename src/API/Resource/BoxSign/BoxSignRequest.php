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

    public array $sign_files;

    public string $signature_color;

    public array $signers = [];

    public File $signing_log;

    public array $source_files;

    public BoxSignStatusType $status;

    public string $template_id;
    
    public function setParentFolder($parent_folder)
    {
        if ($parent_folder instanceof Folder) {
            $this->parent_folder = $parent_folder;
        } else {
            $this->parent_folder = new Folder($this->token);
            $this->parent_folder->hydrate($parent_folder);
        }
        return $this;
    }
    
    public function setSignFiles($sign_files)
    {
        if ($sign_files instanceof Files) {
            $this->sign_files = $sign_files;
        } else {
            $this->sign_files = new Files();
            $this->sign_files->hydrate($sign_files);
        }
        return $this;
    }
    
    public function setSourceFiles($source_files)
    {
        if ($source_files instanceof Files) {
            $this->source_files = $source_files;
        } else {
            $this->source_files = new Files();
            $this->source_files->hydrate($source_files);
        }
        return $this;
    }
    
    public function setStatus($status)
    {
        if ($status instanceof BoxSignStatusType) {
            $this->status = $status;
        } else {
            $this->status = BoxSignStatusType::from($status);
        }
        return $this;
    }
    
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
    
    public function list_box_sign_requests(): BoxSignRequests|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/sign_requests';
        $params = [ // -- No Parameters Required --//
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode()) {
            case 200:
                /**
                 * A list of Box Doc Gen jobs.
                 */
                $requests  = new BoxSignRequests();
                $requests->hydrate($this->response);
                return $requests;
            default:
                /**
                 * An unexpected client error.
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