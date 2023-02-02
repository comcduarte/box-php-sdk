<?php
namespace Laminas\Box\API;

use Laminas\Box\API\Resource\AbstractResource;
use Laminas\Json\Json;
use InvalidArgumentException;

class AccessToken extends AbstractResource
{
    const API_URL = 'https://api.box.com';
    
    const REQUEST_URI = '/oauth2/token';
    const REFRESH_URI = '/oauth2/token';
    const REVOKE_URI = '/oauth2/revoke';
    
    protected $content_type = 'application/x-www-form-urlencoded';
    
    public $actor_token;
    public $actor_token_type;
    public $assertion;
    public $box_shared_link;
    public $box_subject_id;
    public $box_subject_type;
    public $client_id;
    public $client_secret;
    public $grant_type;
    public $refresh_token;
    public $resource;
    public $scope;
    public $subject_token;
    public $subject_token_type;
    
    private $access_token;
    private $expires_in;
    private $restricted_to;
    private $token_type;
    
    public function __construct(
        $client_id, 
        ?string $client_secret = null, 
        ?string $grant_type = null, 
        ?string $box_subject_type = null,
        ?string $box_subject_id = null)
    {
        $this->requires_authorization = false;
        parent::__construct();
        
        $parameters = [];
        
        if (is_array($client_id)) {
            $parameters = $client_id;
        } elseif (! is_string($client_id) & strlen($client_id) == 32) {
            throw new InvalidArgumentException(
                'The supplied or instantiated client_id is not valid.'
                );
        }
        
        /**
         * @TODO Check all parameters for validity.
         */
        $this->exchangeArray($parameters);
        
        $this->request_access_token($parameters);
    }
    
    public function request_access_token(array $parameters)
    {
        $this->requires_authorization = false;
        
        $endpoint = 'https://api.box.com/oauth2/token';
        
        $response = $this->post($endpoint, $parameters);
        
        switch ($response->getStatusCode())
        {
            case 200:
                //-- OK --//
                $a = Json::decode($response->getContent());
                $this->access_token = $a->access_token;
                $this->expires_in = $a->expires_in;
                $this->restricted_to = $a->restricted_to;
                $this->token_type = $a->token_type;
                return TRUE;
            case 400:
                //-- Bad Request --//
                return false;
            default:
                return FALSE;
        }
    }
    
    public function refresh_access_token()
    {
        
    }
    
    public function revoke_access_token()
    {
        
    }
    
    public function getBoxSubjectId()
    {
        return $this->box_subject_id;
    }
    
    public function setBoxSubjectId($box_subject_id)
    {
        $this->box_subject_id = $box_subject_id;
    }

    public function getBoxSubjectType()
    {
        return $this->box_subject_type;
    }
    
    public function setBoxSubjectType($box_subject_type)
    {
        $this->box_subject_type = $box_subject_type;
    }
    
    public function getClientId()
    {
        return $this->client_id;
    }
    
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }
    
    public function getClientSecret()
    {
        return $this->client_secret;
    }
    
    public function setClientSecret($client_secret)
    {
        $this->client_secret = $client_secret;
    }
    
    public function getGrantType()
    {
        return $this->grant_type;
    }
    
    public function setGrantType($grant_type)
    {
        $this->grant_type = $grant_type;
    }
    
    public function getAccessToken()
    {
        if (!isset($this->access_token)) {
            $this->request_access_token();
        } 
        return $this->access_token;
    }
}