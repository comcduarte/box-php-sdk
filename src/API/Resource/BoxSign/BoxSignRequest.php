<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\BoxSign;

use comcduarte\Box\API\Enum\ResourceType;
use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Resource\File;
use comcduarte\Box\API\Resource\Files;
use comcduarte\Box\API\Resource\Folder;
use comcduarte\Box\API\Resource\ClientError;
use comcduarte\Box\API\Exception\ClientErrorException;
use comcduarte\Box\API\Resource\Query;

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
    
    public function getParentFolder(): Folder
    {
        return $this->parent_folder;
    }
    
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
    
    public function getSignFiles(): ?array
    {
        return isset($this->sign_files)
            ? $this->sign_files
            : null;
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
    
    public function getSigningLog(): ?File
    {
        return isset($this->signing_log)
            ? $this->signing_log
            : null;
    }
    
    public function setSigningLog($signing_log)
    {
        if ($signing_log instanceof File) {
            $this->signing_log = $signing_log;
        } else {
            $this->signing_log = new File($this->token);
            $this->signing_log->hydrate($signing_log);
        }
        return $this;
    }
    
    public function getSourceFiles()
    {
        return $this->source_files;
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
    
    public function getStatus()
    {
        return $this->status;
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
    
    public function get_box_sign_request_by_id(string $sign_request_id): self|ClientError
    {
        if (!isset($sign_request_id)) {
            throw new ClientErrorException('No sign_request_id parameter.');
        }
        
        $endpoint = 'https://api.box.com/2.0/sign_requests/:sign_request_id';
        $params = [
            ':sign_request_id' => $sign_request_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a signature request.
                 */
                $this->hydrate($this->response);
                return $this;
            case 404:
                /**
                 * Returns an error when the signature request cannot be found, the user does not have access to the signature request, or sign_files and/or parent_folder is deleted.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    public function list_box_sign_requests(?Query $query): BoxSignRequests|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/sign_requests';
        $params = [ // -- No Parameters Required --//
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = http_build_query($query->getArrayCopy());
        }
        
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
    
    public function cancel_box_sign_request(string $sign_request_id): self|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/sign_requests/:sign_request_id/cancel';
        
        $params = [
            ':sign_request_id' => $sign_request_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, null);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns an empty response when the API call was successful. The email notifications will be sent asynchronously.
                 */
                $this->hydrate($this->response);
                return $this;
            case 404:
                /**
                 * Returns an error when the signature request cannot be found or the user does not have access to the signature request.
                 */
            default:
                /**
                 * An unexpected client error
                 */
                return $this->error();
        }
    }
    
    public function resend_box_sign_request(string $sign_request_id): self|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/sign_requests/:sign_request_id/resend';
        
        $params = [
            ':sign_request_id' => $sign_request_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, null);
        
        switch ($this->response->getStatusCode())
        {
            case 202:
                /**
                 * Returns an empty response when the API call was successful. The email notifications will be sent asynchronously.
                 */
                return $this;
            case 404:
                /**
                 * Returns an error when the signature request cannot be found or the user does not have access to the signature request.
                 */
            default:
                /**
                 * An unexpected client error
                 */
                return $this->error();
        }
    }
    
    public function jsonSerialize(): mixed
    {
        $data = parent::jsonSerialize();
        
        foreach (array_keys(get_object_vars($this)) as $property) {
            $reflectionProperty = new \ReflectionProperty($this, $property);
            if ($reflectionProperty->hasType()) {
                //-- Property has a declared type --//
                $reflectionType = $reflectionProperty->getType();
                switch ($reflectionType)
                {
                    case 'string':
                    case 'array':
                    case 'int':
                    case 'bool':
                        $data[$property] = $this->$property;
                        break;
                    default:
                        $property = lcfirst(str_replace('_', '', ucwords($property, '_')));
                        $getter   = sprintf('get%s', ucfirst($property));
                        $callable = [$this, $getter];
                        if (!is_callable($callable)) {
                            throw new \Exception(
                                sprintf('Unable to call %s', $getter)
                                );
                        }
                        $data[$property] = call_user_func($callable);
                        break;
                }
            } 
        }
        
        return $data;
    }
}