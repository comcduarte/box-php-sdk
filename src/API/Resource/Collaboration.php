<?php
namespace Laminas\Box\API\Resource;


use Laminas\Box\API\RequestExtraFieldsTrait;

class Collaboration extends AbstractResource
{
    use RequestExtraFieldsTrait;
    
    public $id;
    
    public $type = 'collaboration';
    
    public $acceptance_requirement_status;
    
    public $accessible_by;
    
    public $acknowledged_at;
    
    public $created_at;
    
    public $expires_at;
    
    public $invite_email;
    
    public $is_access_only;
    
    public $item;
    
    public $modified_at;
    
    public $role;
    
    public $status;
    
    public function get_collaboration(string $collaboration_id = null)
    {
        if (!isset($collaboration_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/collaborations/:collaboration_id';
        $params = [
            ':collaboration_id' => $collaboration_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            
            case 200:
                /**
                 * Returns a collaboration object.
                 * @var \Laminas\Box\API\Resource\Collaboration $collaboration
                 */
                $this->hydrate($this->response);
                return $this;
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
     * Use constants from Role Object
     * 
     * @param User $accessibly_by
     * @param File|Folder $item
     * @param String $role
     */
    public function create_collaboration($accessibly_by = null, $item = null, $role = null)
    {
        if (!isset($accessibly_by) || !isset($item) || !isset($role)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/collaborations';
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $data = [
            'accessible_by' => [
                'type' => $accessibly_by->type,
                'login' => $accessibly_by->login,
            ],
            'item' => [
                'type' => $item->type,
                'id' => $item->id,
            ],
            'role' => $role,
        ];
        
        $uri = strtr($endpoint, $params);
        $response = $this->post($uri, $data);
        
        switch ($response->getStatusCode())
        {
            case 201:
                $this->hydrate($this->response);
                return $this;
            case 403:
                /**
                 * Returns an error when the user does not have the right 
                 * permissions to create the collaboration.
                 * forbidden_by_policy: Creating a collaboration is forbidden due to information barrier restrictions.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                $error = new ClientError();
                $error->hydrate($this->getResponse());
                return $error;
        }
    }
}