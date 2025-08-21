<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource;

use Laminas\Http\Response;

class MetadataTemplate extends AbstractResource
{
    /**
     * 
     * @var string
     */
    protected string $content_type = 'application/json';
    
    /**
     * The ID of the metadata template.
     * 
     * @var string
     */
    public string $id;
    
    /**
     * Value is always metadata_template.
     * @var string
     */
    public string $type = 'metadata_template';
    
    /**
     * Whether or not to include the metadata when a file or folder is copied.
     * 
     * @var boolean
     */
    public bool $copyInstanceOnItemCopy;
    
    /**
     * The display name of the template. This can be seen in the Box web app and mobile apps.
     * 
     * @var string
     */
    public string $displayName;
    
    /**
     * An ordered list of template fields which are part of the template. 
     * Each field can be a regular text field, date field, number field, as well as a single or multi-select list.
     * 
     * @var Fields
     */
    public Fields $fields;
    
    /**
     * Defines if this template is visible in the Box web app UI, or if it is purely intended for usage through the API.
     * 
     * @var boolean
     */
    public bool $hidden;
    
    /**
     * The scope of the metadata template can either be global or enterprise_*. 
     * The global scope is used for templates that are available to any Box enterprise. 
     * The enterprise_* scope represents templates that have been created within a specific enterprise, 
     * where * will be the ID of that enterprise.
     * 
     * @var string
     */
    public string $scope;
    
    /**
     * A unique identifier for the template. This identifier is unique across the scope of the enterprise 
     * to which the metadata template is being applied, yet is not necessarily unique across different enterprises.
     * 
     * @var string
     */
    public string $templateKey;
    
    public function find_metadata_template_by_instance_id() {}
    
    /**
     * Retrieves a metadata template by its scope and templateKey values.
     * To find the scope and templateKey for a template, list all templates 
     * for an enterprise or globally, or list all templates applied to a file 
     * or folder.
     * @param string $scope
     * @param string $template_key
     * @return boolean
     */
    public function get_metadata_template_by_name(string $scope, string $template_key) 
    {
        if (!isset($scope) | !isset($template_key)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/metadata_templates/:scope/:template_key/schema';
        $params = [
            ':scope' => $scope,
            ':template_key' => $template_key,
        ];
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
    }
    
    /**
     * 
     * @param string $id
     */
    public function get_metadata_template_by_id(string $template_id)
    {
        if (!isset($template_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/metadata_templates/:template_id';
        $params = [
            ':template_id' => $template_id,
        ];
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
    }
    
    /**
     * 
     * @param string $scope
     * @return MetadataTemplates|ClientError
     */
    private function list_all_metadata_templates(string $scope): MetadataTemplates | ClientError
    {
        $endpoint = "https://api.box.com/2.0/metadata_templates/$scope";
        
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns all of the metadata templates available to all enterprises and their corresponding schema.
                 */
                $metadata_templates = new MetadataTemplates();
                $metadata_templates->hydrate($this->response);
                return $metadata_templates;
            case 400:
                /**
                 * Returned when the request parameters are not valid.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     *
     * @return MetadataTemplates
     */
    public function list_all_global_metadata_templates(): MetadataTemplates
    {
        return $this->list_all_metadata_templates('global');
    }
    
    /**
     * 
     * @return MetadataTemplates
     */
    public function list_all_metadata_templates_for_enterprise(): MetadataTemplates
    {
        return $this->list_all_metadata_templates('enterprise');
    }
    
    /**
     * 
     * @return MetadataTemplate|ClientError
     */
    public function create_metadata_template(): MetadataTemplate | ClientError
    {
        $endpoint = 'https://api.box.com/2.0/metadata_templates/schema';
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $data = [
            'scope' => $this->scope,
            'displayName' => $this->displayName,
            'fields' => $this->fields,
            'hidden' => $this->hidden,
            'templateKey' => $this->templateKey,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                /**
                 * The schema representing the metadata template created.
                 */
                $this->hydrate($this->response);
                return $this;
            case 400:
                /**
                 * Returned if the request parameters or body is not valid.
                 * bad_request when the body does not contain a valid request. In many cases this response will include extra details on what fields are missing.
                 */
            case 403:
                /**
                 * Returned when the user does not have the permission to create the metadata template. This can happen for a few reasons, most commonly when the user does not have (co-)admin permissions, or the application tries to create a template with the global scope.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    public function update_metadata_template() {}
    
    /**
     * 
     * @param string $scope
     * @param string $template_key
     * @return Response|ClientError
     */
    public function remove_metadata_template(string $scope, string $template_key): Response | ClientError
    {
        $endpoint = 'https://api.box.com/2.0/metadata_templates/:scope/:template_key/schema';
        $params = [
            ':scope' => $scope,
            ':template_key' => $template_key
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 204:
                /**
                 * Returns an empty response when the metadata template is successfully deleted.
                 */
                return $this->response;
            case 400:
                /**
                 * Request body does not contain a valid metadata schema.
                 */
            case 403:
                /**
                 * Request body contains a scope that the user is not allowed to create a template for.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
}