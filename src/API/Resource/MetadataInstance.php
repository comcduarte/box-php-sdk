<?php
namespace Laminas\Box\API\Resource;


/**
 * 
 * @author Christopher Duarte
 * @copyright 2022
 * 
 * POST https://api.box.com/2.0/files/[:file_id]/metadata/[:scope]/[:template_key]
 */
class MetadataInstance extends File
{
    public const API_FUNC = '/metadata/';
    
    /**
     * Whether the user can edit this metadata instance.
     * 
     * @var boolean
     */
    public $canEdit;
    
    /**
     * A UUID to identify the metadata instance.
     * 
     * @var string
     */
    public $id;
    
    /**
     * The identifier of the item that this metadata instance has been attached to. This combines the type and the id of the parent in the form {type}_{id}.
     * 
     * @var string
     */
    public $parent;
    
    /**
     * An ID for the scope in which this template has been applied. This will be enterprise_{enterprise_id} for templates defined for use in this enterprise, 
     * and global for general templates that are available to all enterprises using Box.
     * 
     * @var string
     */
    public $scope;
    
    /**
     * The name of the template
     * 
     * @var string
     */
    public $template;
    
    /**
     * A unique identifier for the "type" of this instance. This is an internal system property and should not be used by a client application.
     * 
     * @var string
     */
    public $type;
    
    /**
     * The last-known version of the template of the object. This is an internal system property and should not be used by a client application.
     * 
     * @var integer
     */
    public $typeVersion;
    
    /**
     * The version of the metadata instance. This version starts at 0 and increases every time a user-defined property is modified.
     * 
     * @var integer
     */
    public $version;
    
    /**
     * @param string $endpoint
     * @param array $params
     * @param string $template_key
     * @param array $data
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    private function create_metadata_instance(string $endpoint, array $params, string $template_key, $data)
    {
        $oTemplate = new MetadataTemplate($this->token);
        $oTemplate->get_metadata_template_by_id($template_key);
        
        $uri = strtr($endpoint, $params);
        
        $this->response = $this->post($uri, $data);
        
        switch ($this->getResponse()->getStatusCode()) {
            case 201:
                /**
                 * Returns the instance of the template that was applied to the file/folder, including the data that was applied to the template.
                 */
                $metadata_instance = new MetadataInstance($this->token);
                $metadata_instance->hydrate($this->response);
                return $metadata_instance;
            case 400:
                /**
                 * Returns an error when the request body is not valid.
                 */
            case 404:
                /**
                 * Returns an error when the file/folder or metadata template could not be found.
                 */
            case 409:
                /**
                 * Returns an error when an instance of this metadata template is already present on the file/folder.
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
     * @param string $endpoint
     * @param array $params
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    private function list_metadata_instances(string $endpoint, array $params)
    {
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->getResponse()->getStatusCode())
        {
            case 200:
                /**
                 * Returns all the metadata associated with a file.
                 * This API does not support pagination and will therefore always return all of the metadata associated to the file.
                 */
                $metadata_instances = new MetadataInstances();
                $metadata_instances->hydrate($this->getResponse());
                return $metadata_instances;
            case 403:
                /**
                 * Returned when the request parameters are not valid.
                 */
            case 404:
                /**
                 * Returned when the user does not have access to the file.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Scope value is one of [global|enterprise_{enterprise_id}]
     * 
     * @param string $file_id
     * @param string $scope
     * @param string $template_key
     */
    public function create_metadata_instance_on_file(string $file_id, string $scope = 'global', string $template_key, $data)
    {
        if (!isset($file_id) | !isset($template_key)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/metadata/:scope/:template_key';
        $params = [
            ':file_id' => $file_id,
            ':scope' => $scope,
            ':template_key' => $template_key,
        ];
        
        return $this->create_metadata_instance($endpoint, $params, $template_key, $data);
    }

    /**
     * Retrieves all metadata for a given file.
     * @param string $file_id
     * @return @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    public function list_metadata_instances_on_file(string $file_id)
    {
        if (! isset($file_id)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/metadata';
        $params = [
            ':file_id' => $file_id,
        ];
        
        return $this->list_metadata_instances($endpoint, $params);
    }
    
    /**
     * Retrieves the instance of a metadata template that has been applied to a file.
     *
     * @param string $file_id
     * @param string $scope
     * @param string $template_key
     */
    public function get_metadata_instances_on_file(string $file_id, string $scope = 'global', string $template_key)
    {
        if (!isset($file_id) | !isset($template_key)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/metadata/:scope/:template_key';
        $params = [
            ':file_id' => $file_id,
            ':scope' => $scope,
            ':template_key' => $template_key,
        ];
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
    }
    
    public function update_metadata_instance_on_file()
    {
        
    }
    
    public function remove_metadata_instance_from_file()
    {
        
    }
    
    public function create_metadata_instance_on_folder(string $folder_id, string $scope = 'global', string $template_key, $data)
    {
        if (!isset($folder_id) | !isset($template_key)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id/metadata/:scope/:template_key';
        $params = [
            ':folder_id' => $folder_id,
            ':scope' => $scope,
            ':template_key' => $template_key,
        ];
        
        return $this->create_metadata_instance($endpoint, $params, $template_key, $data);
    }
    
    public function list_metadata_instances_on_folder(string $folder_id)
    {
        if (! isset($folder_id)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id/metadata';
        $params = [
            ':folder_id' => $folder_id,
        ];
        
        return $this->list_metadata_instances($endpoint, $params);
    }
    
    public function get_metadata_instances_on_folder()
    {
        
    }
        
    public function update_metadata_instance_on_folder()
    {
        
    }
    
    public function remove_metadata_instance_from_folder()
    {
        
    }
}