<?php
namespace Laminas\Box\API\Resource;

class Folder extends AbstractResource
{
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
    
    public function get_folder_information(string $folder_id = null)
    {
        if (!isset($folder_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id';
        $params = [
            ':folder_id' => $folder_id,
        ];
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a folder, including the first 100 entries in the folder.
                 * To fetch more items within the folder, please use the Get items in a folder endpoint.
                 * Not all available fields are returned by default. Use the fields query parameter to explicitly request any specific fields.
                 * @var \Laminas\Box\API\Resource\Folder $folder
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
                $error = new ClientError();
                $error->hydrate($this->response);
                return $error;
        }
    }
    
    /**
     * Retrieves a page of items in a folder. These items can be files, folders, and web links.
     * @param string $folder_id
     * @return Items|ClientError
     */
    public function list_items_in_folder(string $folder_id = null)
    {
        if (!isset($folder_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id/items';
        $params = [
            ':folder_id' => $folder_id,
        ];
        $uri = strtr($endpoint, $params);
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
                $error = new ClientError();
                $error->hydrate($this->response);
                return $error;
        }
    }
    
    /**
     * Creates a new empty folder within the specified parent folder.
     * @param string $parent_id
     * @param string $name
     * @return boolean|\Laminas\Box\API\Resource\Folder
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
        $response = $this->post($uri, $data);
        
        switch ($response->getStatusCode())
        {
            case 201:
                //-- OK --//
                $folder = new Folder($this->token);
                $folder->hydrate($response);
                return $folder;
            case 409:
                /**
                 * item_name_in_use.
                 */
                $content = \Laminas\Json\Json::decode($this->response->getContent());
                $folder = new Folder($this->token);
                $folder->get_folder_information($content->context_info->conflicts[0]->id);
                return $folder;
            default:
                $content = \Laminas\Json\Json::decode($response->getContent());
                throw new \Exception($content->message, $content->status);
        }
    }
    
    public function copy_folder()
    {
        
    }
    
    public function update_folder()
    {
        
    }
    
    public function delete_folder()
    {
        
    }
}