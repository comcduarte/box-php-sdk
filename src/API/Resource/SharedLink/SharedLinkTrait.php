<?php
declare(strict_types=1);

namespace comcduarte\Box\API\Resource\SharedLink;

use comcduarte\Box\API\Exception\ClientErrorException;
use comcduarte\Box\API\Resource\Query;
use comcduarte\Box\API\Resource\SharedLink;

trait SharedLinkTrait
{
    /**
     * The shared link for this folder. This will be [null] if no shared link has been created for this folder.
     *
     * @var object
     */
    public SharedLink $shared_link;
    
    public function getSharedLink(): SharedLink
    {
        return $this->shared_link;
    }
    
    public function setSharedLink($shared_link): self
    {
        if ($shared_link instanceof SharedLink) {
            $this->shared_link = $shared_link;
        } else {
            $this->shared_link = new SharedLink();
            $this->shared_link->hydrate($shared_link);
        }
        return $this;
    }
    
    private function add_shared_link(string $endpoint, array $params)
    {
        $data = [
            'shared_link' => [
                'access' => $this->getSharedLink()->getAccess(),
                'password' => $this->getSharedLink()->getPassword(),
                'unshared_at' => $this->getSharedLink()->getUnsharedAt()->format(\DateTime::ISO8601),
                'permissions' => $this->getSharedLink()->getPermissions()->getArrayCopy()
            ],
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->put($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * OK
                 */
                $this->hydrate($this->response);
                return $this;
            case 400:
            case 401:
            case 403:
            case 404:
            case 405:
            case 412:
                /**
                 * Method not allowed
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    public function add_shared_link_to_file(string $file_id = null, Query $query = null)
    {
        if (!isset($file_id)) {
            return new ClientErrorException('Required Parameter Missing.');
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id#add_shared_link';
        $params = [
            ':file_id' => $file_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = http_build_query($query->getArrayCopy());
        }
        
        return $this->add_shared_link($endpoint, $params);
    }
    
    public function add_shared_link_to_folder(string $folder_id = null, Query $query = null)
    {
        if (!isset($folder_id)) {
            return new ClientErrorException('Required Parameter Missing.');
        }
        
        $endpoint = 'https://api.box.com/2.0/folders/:folder_id#add_shared_link';
        $params = [
            ':folder_id' => $folder_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = http_build_query($query->getArrayCopy());
        }
        
        return $this->add_shared_link($endpoint, $params);
    }
}