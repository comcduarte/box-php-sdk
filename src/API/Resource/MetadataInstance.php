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
     * Retrieves all metadata for a given file.
     * 
     * @param string $id
     * @return boolean
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
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
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
        
        $oTemplate = new MetadataTemplate($this->token);
        $oTemplate->get_metadata_template_by_id($template_key);
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/metadata/:scope/:template_key';
        $params = [
            ':file_id' => $file_id,
            ':scope' => $scope,
            ':template_key' => $template_key,
        ];
        $uri = strtr($endpoint, $params);
        
        $this->response = $this->post($uri, $data);
        
        /**
         * @TODO Error Reporting
         * 201 Created
         * 409 Conflict - Metadata already exists.
         */
        switch ($this->getResponse()->getStatusCode()) {
            case 201:
                /**
                 * Returns the instance of the template that was applied to the file, including the data that was applied to the template.
                 */
                $retval = $this;
            case 400:
                /**
                 * Returns an error when the request body is not valid.
                 */
            case 404:
                /**
                 * Returns an error when the file or metadata template could not be found.
                 */
            case 409:
                /**
                 * Returns an error when an instance of this metadata template is already present on the file.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                $retval = new \Exception($this->getResponse()->getContent(), $this->getResponse()->getStatusCode());
                break;
        }
        
        return $retval;
    }

    public function update_metadata_instance_on_file()
    {
        
    }
    
    public function remove_metadata_instance_from_file()
    {
        
    }
    
    public function list_metadata_instances_on_folder()
    {
        
    }
    
    public function get_metadata_instances_on_folder()
    {
        
    }
    
    public function create_metadata_instance_on_folder()
    {
        
    }
    
    public function update_metadata_instance_on_folder()
    {
        
    }
    
    public function remove_metadata_instance_from_folder()
    {
        
    }
}