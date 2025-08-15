<?php
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\RequestExtraFieldsTrait;

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
    public $id;
    
    /**
     * 
     * @var string
     */
    public $type = 'folder';
    
    /**
     * 
     * @var string|array
     */
    public $allowed_invitee_roles;
    
    /**
     * 
     * @var string|array
     */
    public $allowed_shared_link_access_levels;
    
    /**
     * 
     * @var boolean
     */
    public $can_non_owners_invite;
    
    /**
     * 
     * @var boolean
     */
    public $can_non_owners_view_collaborators;
    
    /**
     * 
     * @var object
     */
    public $classification;
    
    /**
     * Date Time
     * @var string|null
     */
    public $content_created_at;
    
    /**
     * Date Time
     * @var string|null
     */
    public $content_modified_at;
    
    /**
     * 
     * @var string
     */
    public $description;
    
    /**
     * 
     * @var string
     */
    public $etag;
    
    /**
     * 
     * @var object
     */
    public $folder_upload_email;
    
    /**
     * 
     * @var boolean
     */
    public $has_collaborations;
    
    /**
     * Specifies if new invites to this folder are restricted to users within the enterprise. This does not affect existing collaborations.
     * 
     * @var boolean
     */
    public $is_collaboration_restricted_to_enterprise;
    
    /**
     * Specifies if this folder is owned by a user outside of the authenticated enterprise.
     * 
     * @var boolean
     */
    public $is_externally_owned;
    
    /**
     * A page of the items that are in the folder.
     * This field can only be requested when querying a folder's information, not when querying a folder's items.
     * 
     * @var object
     */
    public $item_collection;
    
    /**
     * Defines if this item has been deleted or not.
     *    [active] when the item has is not in the trash
     *    [trashed] when the item has been moved to the trash but not deleted
     *    [deleted] when the item has been permanently deleted.
     * Value is one of active,trashed,deleted.
     *    
     * @var string
     */
    public $item_status;
    
    /**
     * An object containing the metadata instances that have been attached to this folder.
     * Each metadata instance is uniquely identified by its scope and templateKey. There can only be one instance of any metadata template attached to each folder. Each metadata instance is nested within an object with the templateKey as the key, which again itself is nested in an object with the scope as the key.
     * 
     * @var array
     */
    public $metadata;
    
    /**
     * The date and time when the folder was last updated. This value may be null for some folders such as the root folder or the trash folder.
     * 
     * @var string
     */
    public $modified_at;
    
    /**
     * User (mini) object
     * 
     * @var object
     */
    public $modified_by;
    
    /**
     * The name of the folder.
     * 
     * @var string
     */
    public $name;
    
    /**
     * The user who owns this folder.
     * 
     * @var object
     */
    public $owned_by;
    
    /**
     * The optional folder that this folder is located within.
     * This value may be null for some folders such as the root folder or the trash folder.
     * 
     * @var Folder
     */
    public $parent;
    
    /**
     * 
     * @var object
     */
    public $path_collection;
    
    /**
     * 
     * @var object
     */
    public $permissions;
    
    /**
     * The time at which this folder is expected to be purged from the trash.
     * 
     * @var string
     */
    public $purged_at;
    
    /**
     * A numeric identifier that represents the most recent user event that has been applied to this item.
     * 
     * @var string
     */
    public $sequence_id;
    
    /**
     * The shared link for this folder. This will be [null] if no shared link has been created for this folder.
     * 
     * @var object
     */
    public $shared_link;
    
    /**
     * The folder size in bytes.
     * Be careful parsing this integer as its value can get very large.
     * 
     * @var integer
     */
    public $size;
    
    /**
     * Specifies whether a folder should be synced to a user's device or not. This is used by Box Sync (discontinued) and is not used by Box Drive.
     * Value is one of synced,not_synced,partially_synced
     * 
     * @var string
     */
    public $sync_state;
    
    /**
     * The tags for this item. These tags are shown in the Box web app and mobile apps next to an item.
     * To add or remove a tag, retrieve the item's current tags, modify them, and then update this field.
     * There is a limit of 100 tags per item, and 10,000 unique tags per enterprise.
     * 
     * @var string|array
     */
    public $tags;
    
    /**
     * The time at which this folder was put in the trash.
     * 
     * @var string
     */
    public $trashed_at;
    
    /**
     * Details about the watermark applied to this folder
     * @var object
     */
    public $watermark_info;
    
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
                $folder = new Folder($this->token);
                $folder->hydrate($this->response);
                
                /**
                 * Hydrate $this object as well.
                 */
                $this->hydrate($this->response);
                return $folder;
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
    
    public function copy_folder()
    {
        
    }
    
    public function update_folder()
    {
        
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