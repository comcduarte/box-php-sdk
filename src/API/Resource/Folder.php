<?php
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\RequestExtraFieldsTrait;
use comcduarte\Box\API\Enum\ResourceType;

class Folder extends AbstractResource
{
    use RequestExtraFieldsTrait;
    
    public const API_FUNC = '/folders/';
    
    /**
     *
     * @var string
     */
    protected $content_type = 'application/json';
    
    /**
     * 
     * @var string
     */
    public ResourceType $type = ResourceType::Folder;
    
    /**
     * 
     * @var string|array
     */
    public array $allowed_invitee_roles;
    
    /**
     * 
     * @var string|array
     */
    public array $allowed_shared_link_access_levels;
    
    /**
     * 
     * @var boolean
     */
    public bool $can_non_owners_invite;
    
    /**
     * 
     * @var boolean
     */
    public bool $can_non_owners_view_collaborators;
    
    /**
     * 
     * @var object
     */
    public \stdClass $classification;
    
    /**
     * Date Time
     * @var string|null
     */
    public string $content_created_at;
    
    /**
     * Date Time
     * @var string|null
     */
    public string $content_modified_at;
    
    public string $created_at;
    
    public User $created_by;
    
    /**
     * 
     * @var string
     */
    public string $description;
    
    /**
     * 
     * @var string
     */
    public string $etag;
    
    /**
     * 
     * @var object
     */
    public \stdClass $folder_upload_email;
    
    /**
     * 
     * @var boolean
     */
    public bool $has_collaborations;
    
    public bool $is_accessible_via_shared_link;
    
    public bool $is_associated_with_app_item;
    
    /**
     * Specifies if new invites to this folder are restricted to users within the enterprise. This does not affect existing collaborations.
     * 
     * @var boolean
     */
    public bool $is_collaboration_restricted_to_enterprise;
    
    /**
     * Specifies if this folder is owned by a user outside of the authenticated enterprise.
     * 
     * @var boolean
     */
    public bool $is_externally_owned;
    
    /**
     * A page of the items that are in the folder.
     * This field can only be requested when querying a folder's information, not when querying a folder's items.
     * 
     * @var object
     */
    public Items $item_collection;
    
    /**
     * Defines if this item has been deleted or not.
     *    [active] when the item has is not in the trash
     *    [trashed] when the item has been moved to the trash but not deleted
     *    [deleted] when the item has been permanently deleted.
     * Value is one of active,trashed,deleted.
     *    
     * @var string
     */
    public string $item_status;
    
    /**
     * An object containing the metadata instances that have been attached to this folder.
     * Each metadata instance is uniquely identified by its scope and templateKey. There can only be one instance of any metadata template attached to each folder. Each metadata instance is nested within an object with the templateKey as the key, which again itself is nested in an object with the scope as the key.
     * 
     * @var array
     */
    public array $metadata;
    
    /**
     * The date and time when the folder was last updated. This value may be null for some folders such as the root folder or the trash folder.
     * 
     * @var string
     */
    public string $modified_at;
    
    /**
     * User (mini) object
     * 
     * @var object
     */
    public User $modified_by;
    
    /**
     * The name of the folder.
     * 
     * @var string
     */
    public string $name;
    
    /**
     * The user who owns this folder.
     * 
     * @var object
     */
    public User $owned_by;
    
    /**
     * The optional folder that this folder is located within.
     * This value may be null for some folders such as the root folder or the trash folder.
     * 
     * @var Folder
     */
    public Folder $parent;
    
    /**
     * 
     * @var object
     */
    public Items $path_collection;
    
    /**
     * 
     * @var object
     */
    public \stdClass $permissions;
    
    /**
     * The time at which this folder is expected to be purged from the trash.
     * 
     * @var string
     */
    public string $purged_at;
    
    /**
     * A numeric identifier that represents the most recent user event that has been applied to this item.
     * 
     * @var string
     */
    public string $sequence_id;
    
    /**
     * The shared link for this folder. This will be [null] if no shared link has been created for this folder.
     * 
     * @var object
     */
    public \stdClass $shared_link;
    
    /**
     * The folder size in bytes.
     * Be careful parsing this integer as its value can get very large.
     * 
     * @var integer
     */
    public int $size;
    
    /**
     * Specifies whether a folder should be synced to a user's device or not. This is used by Box Sync (discontinued) and is not used by Box Drive.
     * Value is one of synced,not_synced,partially_synced
     * 
     * @var string
     */
    public string $sync_state;
    
    /**
     * The tags for this item. These tags are shown in the Box web app and mobile apps next to an item.
     * To add or remove a tag, retrieve the item's current tags, modify them, and then update this field.
     * There is a limit of 100 tags per item, and 10,000 unique tags per enterprise.
     * 
     * @var string|array
     */
    public array $tags;
    
    /**
     * The time at which this folder was put in the trash.
     * 
     * @var string
     */
    public string $trashed_at;
    
    /**
     * Details about the watermark applied to this folder
     * @var object
     */
    public \stdClass $watermark_info;
    
    public function __construct($access_token = null)
    {
        parent::__construct($access_token);
        $this->item_collection = new Items();
        return $this;
    }
    
    public function getCreatedBy()
    {
        return $this->created_by;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getPathCollection()
    {
        return $this->path_collection;
    }
    
    public function setCreatedBy($created_by)
    {
        if ($created_by instanceof User) {
            $this->created_by = $created_by;
        } else {
            $this->created_by = new User($this->token);
            $this->created_by->hydrate($created_by);
        }
        return $this;
    }
    
    public function setItemCollection($item_collection): self
    {
        if ($item_collection instanceof Items) {
            $this->item_collection = $item_collection;
        } else {
            $this->item_collection = new Items();
            $this->item_collection->hydrate($item_collection);
        }
        return $this;
    }
    
    public function setModifiedBy($modified_by)
    {
        if ($modified_by instanceof User) {
            $this->created_by = $modified_by;
        } else {
            $this->modified_by = new User($this->token);
            $this->modified_by->hydrate($modified_by);
        }
        return $this;
    }
    
    public function setOwnedBy($owned_by)
    {
        if ($owned_by instanceof User) {
            $this->created_by = $owned_by;
        } else {
            $this->owned_by = new User($this->token);
            $this->owned_by->hydrate($owned_by);
        }
        return $this;
    }
    
    public function setParent($parent): self
    {
        if ($parent instanceof Folder) {
            $this->parent = $parent;
        } else {
            $this->parent = new Folder($this->token);
            $this->parent->hydrate($parent);
        }
        return $this;
    }
    
    public function setPathCollection($path_collection): self
    {
        if ($path_collection instanceof Items) {
            $this->path_collection = $path_collection;
        } else {
            $this->path_collection = new Items();
            $this->path_collection->hydrate($path_collection);
        }
        return $this;
    }
    
    /**
     * Retrieves details for a folder, including the first 100 entries in the folder.
     * Passing sort, direction, offset, and limit parameters in query allows you to manage the list of returned folder items.
     * To fetch more items within the folder, use the Get items in a folder endpoint.
     * @param string $folder_id
     * @param Query $query
     * @return boolean|\comcduarte\Box\API\Resource\Folder|\comcduarte\Box\API\Resource\ClientError
     */
    public function get_folder_information(string $folder_id = null, Query $query = null)
    {
        if (!isset($folder_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id';
        $params = [
            ':folder_id' => $folder_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query->getArrayCopy() as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a folder, including the first 100 entries in the folder.
                 * To fetch more items within the folder, please use the Get items in a folder endpoint.
                 * Not all available fields are returned by default. Use the fields query parameter to explicitly request any specific fields.
                 * @var \comcduarte\Box\API\Resource\Folder $folder
                 */
                $this->hydrate($this->response);
                return $this;
            case 403:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            case 404:
                /**
                 * Returned if the folder is not found, or the user does not have access to the folder.
                 */
            case 405:
                /**
                 * Returned if the folder_id is not in a recognized format.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Retrieves a page of items in a folder. These items can be files, folders, and web links.
     * @param string $folder_id
     * @return Items|ClientError
     */
    public function list_items_in_folder(string $folder_id = null, Query $query = null)
    {
        if (!isset($folder_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id/items';
        $params = [
            ':folder_id' => $folder_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query->getArrayCopy() as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a collection of files, folders, and web links contained in a folder.
                 */
                $items = new Items();
                $items->hydrate($this->response);
                return $items;
            case 403:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            case 404:
                /**
                 * Returned if the folder is not found, or the user does not have access to the folder.
                 */
            case 405:
                /**
                 * Returned if the folder_id is not in a recognized format.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Creates a new empty folder within the specified parent folder.
     * @param string $parent_id
     * @param string $name
     * @return boolean|\comcduarte\Box\API\Resource\Folder
     */
    public function create_folder(string $parent_id = null, string $name = null)
    {
        if (!isset($parent_id) || !isset($name)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders';
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $data = [
            'name' => $name,
            'parent' => [
                'id' => $parent_id
            ],
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                //-- OK --//
                $folder = new Folder($this->token);
                $folder->hydrate($this->response);
                return $folder;
            case 400:
                /**
                 * Returns an error if some of the parameters are missing or not valid.
                 * bad_request when a parameter is missing or incorrect.
                 * item_name_too_long when the folder name is too long.
                 * item_name_invalid when the folder name contains non-valid characters.
                 */
            case 403:
                /**
                 * Returns an error if the user does not have the required access to perform
                 * the action. This might be because they don't have access to the folder or
                 * parent folder, or because the application does not have permission to
                 * write files and folders.
                 */
                $content = '{"type":"error","status":403,"code":"insufficient_scope","context_info":{"errors":[{"reason":"insufficient_scope","name":"item","message":"The request requires higher privileges than provided by the access token."}]},"help_url":"http:\/\/developers.box.com\/docs\/#errors","message":"Not Found","request_id":"yupg0ohrkrkf4jw3"}';
                $this->response->setContent($content);
            case 404:
                /**
                 * Returns an error if the parent folder could not be found, or the
                 * authenticated user does not have access to the parent folder.
                 *
                 * not_found when the authenticated user does not have access to the
                 * parent folder
                 */
            case 409:
                /**
                 *
                 * operation_blocked_temporary: Returned if either of the destination or source folders is locked due to another move, copy, delete or restore operation in process.
                 * The operation can be retried at a later point.
                 * item_name_in_use: Returned if a folder with the name already exists in the parent folder.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    public function copy_folder(string $folder_id, string $parent)
    {
        
    }
    
    public function move_folder(string $folder_id, string $parent)
    {
        
    }
    
    public function update_folder(string $folder_id, array $data): Folder|ClientError
    {
        if (!isset($folder_id)) {
            $error = new ClientError();
            $error->status = '404';
            return $error;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id';
        $params = [
            ':folder_id' => $folder_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->put($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a folder object for the updated folder
                 * 
                 * Not all available fields are returned by default. Use the fields query parameter to 
                 * explicitly request any specific fields.
                 * 
                 * If the user is moving folders with a large number of items in all of their descendants, 
                 * the call will be run asynchronously. If the operation is not completed within 10 minutes, 
                 * the user will receive a 200 OK response, and the operation will continue running.
                 */
                $folder = new Folder($this->token);
                $folder->hydrate($this->response);
                return $folder;
            case 400:
                /**
                 * Returns an error if some of the parameters are missing or not valid, or if a folder lock is preventing a move operation.
                 *     bad_request when a parameter is missing or incorrect. This error also happens when a password is set for a shared link with an access type of open.
                 *     item_name_too_long when the folder name is too long.
                 *     item_name_invalid when the folder name contains non-valid characters.

                 */
            case 403:
                /**
                 * Returns an error if the user does not have the required access to perform the action.
                 *    access_denied_insufficient_permissions: Returned when the user does not have access to the folder or parent folder, or if the folder is being moved and a folder lock has been applied to prevent such operations.
                 *     insufficient_scope: Returned an error if the application does not have the right scope to update folders. Make sure your application has been configured to read and write all files and folders stored in Box.
                 *     forbidden: Returned when the user is not allowed to perform this action for other users. This can include trying to create a Shared Link with a company access level on a free account.
                 *     forbidden_by_policy: Returned if copying a folder is forbidden due to information barrier restrictions.
                 * Returns an error if there are too many actions in the request body.
                 *     operation_limit_exceeded: Returned when the user passes any parameters in addition to the parent.id in the request body. The calls to this endpoint have to be split up. The first call needs to include only the parent.id, the next call can include other parameters.
                 */
            case 404:
                /**
                 * operation_blocked_temporary: Returned if either of the destination or source folders is locked due to another move, copy, delete or restore operation in progress.
                 * The operation can be retried at a later point.
                 * item_name_in_use: Returned if a folder with the name already exists in the parent folder.
                 */
            case 412:
                /**
                 * Returns an error when the If-Match header does not match the current etag value of the folder. This indicates that the folder has changed since it was last requested.
                 */
            case 503:
                /**
                 * Returns an error when the operation takes longer than 600 seconds. The operation will continue after this response has been returned.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
        
    }
    
    public function delete_folder(string $folder_id = null, bool $recursive = false)
    {
        if (!isset($folder_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id';
        $params = [
            ':folder_id' => $folder_id,
        ];
        
        if (isset($recursive)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            $params[':query'] .= sprintf('%s=%s', 'recursive', 'true');
            
        }
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 204:
                /**
                 * Returns an empty response when the folder is successfully deleted or moved to the trash.
                 */
                return null;
            case 400:
                /**
                 * Returns an error if the user makes a bad request.
                 */
            case 403:
                /**
                 * Returns an error if the user does not have the required access to perform the action.
                 */
            case 404:
                /**
                 * Returns an error if the folder could not be found, or the authenticated user does not have access to the folder.
                 */
            case 409:
                /**
                 * operation_blocked_temporary: Returned if the folder is locked due to another move, copy, delete or restore operation in progress.
                 */
            case 412:
                /**
                 * Returns an error when the If-Match header does not match the current etag value of the folder. This indicates that the folder has changed since it was last requested.
                 */
            case 503:
                /**
                 * Returns an error when the operation takes longer than 60 seconds. The operation will continue after this response has been returned.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Retrieves a list of pending and active collaborations for a folder. This returns all the users that have access to the folder or have been invited to the folder.
     * @param string $folder_id
     * @return Collaborations|ClientError
     */
    public function listFolderCollaborations(string $folder_id = null)
    {
        if (!isset($folder_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id/collaborations';
        $params = [
            ':folder_id' => $folder_id,
        ];
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a collection of collaboration objects. If there are no collaborations on this folder an empty collection will be returned.
                 * This list includes pending collaborations, for which the status is set to pending, indicating invitations that have been sent but not yet accepted.
                 * @var \comcduarte\Box\API\Resource\Folder $folder
                 */
                $json = $this->response->getContent();
                $ary = json_decode($json, true);
                
                $collaborations = new Collaborations($this->token);
                foreach ($ary['entries'] as $key => $entry) {
                    $collaboration = new Collaboration($this->token);
                    $collaboration->hydrate($entry);
                    $collaborations->entries[$key] = $collaboration;
                }
                return $collaborations;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
}