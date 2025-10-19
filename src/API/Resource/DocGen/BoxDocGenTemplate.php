<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource\DocGen;

use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Resource\ClientError;
use comcduarte\Box\API\Resource\File;
use comcduarte\Box\API\Enum\ResourceType;

class BoxDocGenTemplate extends AbstractResource
{
    public string $id = '';
    
    public ResourceType $type = ResourceType::File;
    
    public string $file_name = '';
    
    public string $version = '2025.0';
    
    public function list_box_doc_gen_templates(): BoxDocGenTemplates|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_templates';
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
                $templates = new BoxDocGenTemplates();
                $templates->hydrate($this->response);
                return $templates;
            case 400:
                /**
                 * Returned if the user has passed in a malformed marker or limit value.
                 */
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

    public function create_box_doc_gen_template(File $file): self|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_templates';
        $params = [
            //-- No Parameters Required --//
        ];
        
        $data = [
            "file" => [
                "id" => $file->id,
                "type" => $file->type,
            ]
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                /**
                 * The file which has now been marked as a Box Doc Gen template.
                 */
                return $this;
            case 400:
                /**
                 * The server cannot or will not process the request due to an apparent client error.
                 */
            case 403:
                /**
                 * The client does not have access rights to the content or resource requested.
                 */
            case 429:
                /**
                 * The user has sent too many requests in a given amount of time.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function delete_box_doc_gen_template(string $template_id): bool|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_templates/:template_id';
        $params = [
            ':template_id' => $template_id,
        ];
        
        
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 204:
                /**
                 * Returns an empty response when a file is no longer marked as a Box Doc Gen template.
                 */
                return true;
            case 400:
                /**
                 * The server cannot or will not process the request due to an apparent client error.
                 */
            case 403:
                /**
                 * The client does not have access rights to the content or resource requested.
                 */
            case 404:
                /**
                 * Returned if the template is not found or the user does not have access to the associated template.
                 */
            case 429:
                /**
                 * The user has sent too many requests in a given amount of time.
                 */
            default:
                return $this->error();
        }
    }

    public function get_box_doc_gen_template(string $template_id): self|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_templates/:template_id';
        $params = [
            ':template_id' => $template_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a template.
                 */
                $this->hydrate($this->response);
                return $this;
            case 401:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            case 404:
                /**
                 * Returned if the template is not found or the user does not have access to the associated template.
                 */
            default:
                return $this->error();
        }
    }

    public function list_all_box_doc_gen_template_tags(string $template_id): BoxDocGenTemplateTags|BoxDocGenTagsProcessingMessage|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/docgen_templates/:template_id/tags';
        $params = [
            ':template_id' => $template_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * A list of document generation template tags.
                 */
                $tags = new BoxDocGenTemplateTags();
                $tags->hydrate($this->response);
                return $tags;
            case 202:
                /**
                 * Processing tags for the file.
                 */
                $message = new BoxDocGenTagsProcessingMessage();
                return $message;
            case 400:
                /**
                 * Returned if the user has passed in a malformed marker or limit value.
                 */
            case 403:
                /**
                 * The client does not have access rights to the content or resource requested.
                 */
            case 404:
                /**
                 * The requested resource could not be found but may be available in the future.
                 */
            case 429:
                /**
                 * The user has sent too many requests in a given amount of time.
                 */
            default:
                return $this->error();
                
        }
    }
}