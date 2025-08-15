<?php
namespace comcduarte\Box\API;

Trait AccessTokenAwareTrait
{
    /**
     * 
     * @var \comcduarte\Box\API\AccessToken
     */
    protected $access_token;
    
    /**
     * 
     * @param \comcduarte\Box\API\AccessToken $access_token
     * @return \comcduarte\Box\API\AccessTokenAwareTrait
     */
    public function setAccessToken(AccessToken $access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }
    
    /**
     * @return \comcduarte\Box\API\AccessToken
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }
}