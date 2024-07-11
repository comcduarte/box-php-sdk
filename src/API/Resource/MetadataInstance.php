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
    private function create_metadata_instance(string $endpoint, array $params, $data)
    {
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->getResponse()->getStatusCode()) {
            case 201:
                /**
                 * Returns the instance of the template that was applied to the file/folder, including the data that was applied to the template.
                 */
                $metadata_instance = new MetadataInstance($this->token);
                
                /**
                * API returns global properties with prefix of $
                * Remove $ and set properties in array before hydrating
                */
                $data = json_decode($this->response->getContent(), true);
                foreach ($data as $key => $value) {
                    $property = trim($key, '$');
                    
                    if (property_exists($this, $property)) {
                        $data[$property] = $value;
                    }
                    
                }
                
                $metadata_instance->hydrate($data);
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
     * 
     * @param string $endpoint
     * @param array $params
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    private function get_metadata_instance(string $endpoint, array $params)
    {
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode()) {
            case 200:
                /**
                 * An instance of the metadata template that includes additional "key:value" pairs defined by the user or an application.
                 */
                $metadata_instance = new MetadataInstance($this->token);
                
                /**
                 * API returns global properties with prefix of $
                 * Remove $ and set properties in array before hydrating
                 */
                $data = json_decode($this->response->getContent(), true);
                foreach ($data as $key => $value) {
                    $property = trim($key, '$');
                    
                    if (property_exists($this, $property)) {
                        $data[$property] = $value;
                    }
                    
                }
                
                $metadata_instance->hydrate($data);
                return $metadata_instance;
            case 403:
                /**
                 * Returned when the request parameters are not valid.
                 */
            case 404:
                /**
                 * Returned if the metadata template specified was not applied to this file or the user does not have access to the file.
                 */
            case 405:
                /**
                 * Returned when the method was not allowed.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    
    private function update_metadata_instance(string $endpoint, array $params, $data)
    {
        $this->content_type = 'application/json-patch+json';
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->put($uri, $data);
        
        switch ($this->getResponse()->getStatusCode()) {
            case 200:
                /**
                 * Returns the updated metadata template instance, with the custom template data included.
                 */
                $metadata_instance = new MetadataInstance($this->token);
                
                /**
                 * API returns global properties with prefix of $
                 * Remove $ and set properties in array before hydrating
                 */
                $data = json_decode($this->response->getContent(), true);
                foreach ($data as $key => $value) {
                    $property = trim($key, '$');
                    
                    if (property_exists($this, $property)) {
                        $data[$property] = $value;
                    }
                    
                }
                
                $metadata_instance->hydrate($data);
                return $metadata_instance;
            case 400:
                /**
                 * Returns an error when the request body is not valid.
                 */
            case 500:
                /**
                 * Returns an error in some edge cases when the request body is not a valid array of JSON Patch items.
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
     * @return NULL|\Laminas\Box\API\Resource\ClientError
     */
    private function remove_metadata_instance(string $endpoint, array $params)
    {
        $uri = strtr($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode()) {
            case 204:
                /**
                 * Returns an empty response when the metadata is successfully deleted.
                 */
                return null;
            case 400:
                /**
                 * Returned when the request parameters are not valid. This may happen of the scope is not valid.
                 */
            case 404:
                /**
                 * Returns an error when the file does not have an instance of the metadata template applied to it, or when the user does not have access to the file.
                 * instance_not_found - An instance of the metadata template with the given scope and templateKey was not found on this file.
                 * not_found - The file was not found, or the user does not have access to the file.
                 */
            case 405:
                /**
                 * Returned when the method was not allowed.
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
        
        return $this->create_metadata_instance($endpoint, $params, $data);
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
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    public function get_metadata_instance_on_file(string $file_id, string $scope = 'global', string $template_key)
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
        
        return $this->get_metadata_instance($endpoint, $params);
        
    }
    
    /**
     * Updates a piece of metadata on a file.
     * The metadata instance can only be updated if the template has already been applied to the file before. When editing metadata, only values that match the metadata template schema will be accepted.
     * The update is applied atomically. If any errors occur during the application of the operations, the metadata instance will not be changed.
     * 
     * @param string $file_id
     * @param string $scope
     * @param string $template_key
     * @param array $data
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    public function update_metadata_instance_on_file(string $file_id, string $scope = 'global', string $template_key, $data)
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
        
        return $this->update_metadata_instance($endpoint, $params, $data);
    }
    
    public function remove_metadata_instance_from_file(string $file_id, string $scope = 'global', string $template_key)
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
        
        return $this->remove_metadata_instance($endpoint, $params);
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
        
        return $this->create_metadata_instance($endpoint, $params, $data);
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
    
    /**
     * Retrieves the instance of a metadata template that has been applied to a Folder
     * @param string $folder_id
     * @param string $scope
     * @param string $template_key
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    public function get_metadata_instance_on_folder(string $folder_id, string $scope = 'global', string $template_key)
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
        
        return $this->get_metadata_instance($endpoint, $params);
    }
    
    /**
     * Updates a piece of metadata on a folder.
     * The metadata instance can only be updated if the template has already been applied to the folder before. When editing metadata, only values that match the metadata template schema will be accepted.
     * The update is applied atomically. If any errors occur during the application of the operations, the metadata instance will not be changed.
     * 
     * @param string $folder_id
     * @param string $scope
     * @param string $template_key
     * @param array $data
     * @return \Laminas\Box\API\Resource\MetadataInstance|\Laminas\Box\API\Resource\ClientError
     */
    public function update_metadata_instance_on_folder(string $folder_id, string $scope = 'global', string $template_key, $data)
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
        
        return $this->update_metadata_instance($endpoint, $params, $data);
    }
    
    public function remove_metadata_instance_from_folder(string $folder_id, string $scope = 'global', string $template_key)
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
        
        return $this->remove_metadata_instance($endpoint, $params);
    }
}