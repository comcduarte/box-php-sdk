<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\ConflictResolutionType;
use comcduarte\Box\API\Enum\ResourceType;

class MetadataCascadePolicy extends AbstractResource
{
    public ResourceType $type = ResourceType::Metadata_Cascade_Policy;

    public array $owner_enterprise;
    
    public BaseResource $parent;
    
    public string $scope = 'enterprise';
    
    public string $templateKey;
    
    /**
     * Retrieves a list of all the metadata cascade policies that are applied to a given folder. 
     * This can not be used on the root folder with ID 0.
     * 
     * @param string $folder_id
     * @param string $marker
     * @param int $offset
     * @param string $owner_enterprise_id
     * @return MetadataCascadePolicies|ClientError
     */
    public function list_metadata_cascade_policies(string $folder_id =null, string $marker = null, int $offset = null, string $owner_enterprise_id = null): MetadataCascadePolicies|ClientError
    {
        $query = array_filter(get_defined_vars(), fn($v) => $v !== null);
        
        $endpoint = 'https://api.box.com/2.0/metadata_cascade_policies';
        $params = [
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = http_build_query($query->getArrayCopy());
        }
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a list of metadata cascade policies.
                 */
                $metadata_cascade_policies = new MetadataCascadePolicies();
                $metadata_cascade_policies->hydrate($this->response);
                return $metadata_cascade_policies;
            case 400:
                /**
                 * Returns an error when any of the parameters are not in a valid format.
                 */
            case 403:
                /**
                 * Returns an error when the folder can not be accessed. This error often happens when accessing the root folder with ID 0.
                 */
            case 404:
                /**
                 * Returns an error when the folder can not be found or the user does not have access to the folder.
                 * not_found - The folder could not be found or the user does not have access to the folder.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Retrieve a specific metadata cascade policy assigned to a folder.
     * 
     * @param string $metadata_cascade_policy_id
     * @return MetadataCascadePolicy|ClientError
     */
    public function get_metadata_cascade_policy(string $metadata_cascade_policy_id): MetadataCascadePolicy|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/metadata_cascade_policies/:metadata_cascade_policy_id';
        $params = [
            ':metadata_cascade_policy_id' => $metadata_cascade_policy_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 */
                $this->hydrate($this->response);
                return $this;
            case 404:
                /**
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Creates a new metadata cascade policy that applies a given metadata template to a given folder and automatically cascades it down to any files within that folder.
     * In order for the policy to be applied a metadata instance must first be applied to the folder the policy is to be applied to.
     * 
     * @param string $folder_id
     * @param string $scope
     * @param string $templateKey
     * @return MetadataCascadePolicy|ClientError
     */
    public function create_metadata_cascade_policy(string $folder_id, string $scope, string $templateKey): MetadataCascadePolicy|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/metadata_cascade_policies';
        $params = [
        ];
        
        $data = [
            'folder_id'   => $folder_id,
            'scope'      => $scope,
            'templateKey' => $templateKey,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                /**
                 */
                $this->hydrate($this->response);
                return $this;
            case 400:
                /**
                 * Returns an error when any of the parameters are not in a valid format.
                 * bad_request - Either the scope, templateKey, or folder_id are not in a valid format.
                 */
            case 403:
                /**
                 * Returns an error when trying to apply a policy to a restricted folder, for example the root folder with ID 0.
                 * forbidden - Although the folder ID was valid and the user has access to the folder, the policy could not be applied to this folder.
                 */
            case 404:
                /**
                 * Returns an error when the template or folder can not be found, or when the user does not have access to the folder or template.
                 * instance_tuple_not_found - The template could not be found or the user does not have access to the template.
                 * not_found - The folder could not be found or the user does not have access to the folder.
                 */
            case 409:
                /**
                 * @todo Technically returns a ConflictError.  This error should be integrated into the SDK.
                 * Returns an error when a policy for this folder and template is already in place.
                 * tuple_already_exists - A cascade policy for this combination of folder_id, scope and templateKey already exists.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
        
    }
    
    /**
     * Force the metadata on a folder with a metadata cascade policy to be applied to all of its children. This can be used after creating a new cascade policy to enforce the metadata to be cascaded down to all existing files within that folder.
     * 
     * @param string $metadata_cascade_policy_id
     * @param ConflictResolutionType $conflict_resolution
     * @return void|ClientError
     */
    public function force_apply_metadata_cascade_policy_to_folder(string $metadata_cascade_policy_id, ConflictResolutionType $conflict_resolution): ull|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/metadata_cascade_policies/:metadata_cascade_policy_id/apply';
        $params = [
            ':metadata_cascade_policy_id' => $metadata_cascade_policy_id,
        ];
        
        $data = [
            'conflict_resolution' => $conflict_resolution->value,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 202:
                /**
                 * Returns an empty response when the API call was successful. The metadata cascade operation will be performed asynchronously.
                 * The API call will return directly, before the cascade operation is complete. There is currently no API to check for the status of this operation.
                 */
                return null;
            case 404:
                /**
                 * Returns an error when the policy can not be found or the user does not have access to the folder.
                 * instance_not_found - The policy could not be found
                 * not_found - The folder could not be found or the user does not have access to the folder.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Deletes a metadata cascade policy.
     * 
     * @param string $metadata_cascade_policy_id
     * @return void|ClientError
     */
    public function remove_metadata_cascade_policy(string $metadata_cascade_policy_id): null|ClientError
    {
        $endpoint = 'https://api.box.com/2.0/metadata_cascade_policies/:metadata_cascade_policy_id';
        $params = [
            ':metadata_cascade_policy_id' => $metadata_cascade_policy_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 204:
                /**
                 * Returns an empty response when the policy is successfully deleted.
                 */
                return null;
            case 404:
                /**
                 * Returns an error when the policy can not be found or the user does not have access to the folder.
                 * instance_not_found - The policy could not be found
                 * not_found - The folder could not be found or the user does not have access to the folder.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    

    public function getParent()
    {
        return $this->parent;
    }
    

    public function setParent($parent)
    {
        if ($parent instanceof BaseResource) {
            $this->parent = $parent;
        } else {
            $this->parent = new BaseResource($this->token);
            $this->parent->hydrate($parent);
        }
        return $this;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType()->value,
            'owner_enterprise' => [
                'id' => $this->owner_enterprise['id'],
                'type' => $this->owner_enterprise['type'],
            ],
            'parent' => [
                'id' => $this->getParent()->getId(),
                'type' => $this->getParent()->getType(),
            ],
            'scope' => $this->scope,
            'templateKey' => $this->templateKey,
        ];
    }
}