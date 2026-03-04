<?php
namespace comcduarte\Box\API;


class Connection 
{
    
    /**
     * 
     * @var \comcduarte\Box\API\AccessToken
     */
    private $access_token;
                
    
    public function __construct ()
    {
        
    }
    
    /**
     * 
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * 
     * @param AccessToken $access_token
     * @return \comcduarte\Box\API\Connection
     */
    public function setAccessToken(AccessToken $access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }
}