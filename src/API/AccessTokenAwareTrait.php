<?php
namespace Laminas\Box\API;

Trait AccessTokenAwareTrait
{
    /**
     * 
     * @var \Laminas\Box\API\AccessToken
     */
    protected $access_token;
    
    /**
     * 
     * @param \Laminas\Box\API\AccessToken $access_token
     * @return \Laminas\Box\API\AccessTokenAwareTrait
     */
    public function setAccessToken(AccessToken $access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }
    
    /**
     * @return \Laminas\Box\API\AccessToken
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }
}