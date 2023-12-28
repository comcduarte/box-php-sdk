<?php
namespace Laminas\Box\API\Resource;

use Laminas\Box\API\RepresentationsTrait;
use Laminas\Http\Response;
use Laminas\Stdlib\ArraySerializableInterface;
use Laminas\Box\API\Exception\ClientErrorException;

class File extends AbstractResource implements ArraySerializableInterface
{
    use HydrationTrait;
    use RepresentationsTrait;
    
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
    public $type = 'file';
    
    /**
     * 
     * @var string
     */
    public $content_created_at;
    
    
    public $created_at;
    
    /**
     * 
     */
    public $created_by;
    public $description;
    public $etag;
    
    /**
     * 
     */
    public $file_version;
    public $item_status;
    public $modified_at;
    public $name;
    public $owned_by;
    public $parent;
    
    /**
     */
    public $path_collection;
    
    public $purged_at;
    public $sequence_id;
    public $sha1;
    
    /**
     * 
     */
    public $shared_link;
    
    public $size;
    public $trashed_at;
    
    /**
     * Retrieves the details about a file.
     * @var string|mixed
     * @return $this |ClientError
     */
    public function get_file_information(string $file_id = null)
    {
        if (!isset($file_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id';
        
        $params = [
            ':file_id' => $file_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                $this->hydrate($this->getResponse());
                return $this;
            case 304:
                /**
                 * @TODO Populate Error Responses.
                 */
            case 401:
            case 404:
            case 405:
            case 415:
            default:
                /**
                 * An unexpected client error.
                 */
                $error = new ClientError();
                $error->hydrate($this->getResponse());
                return $error;
        }
    }
    
    public function get_file_thumbnail() {}
    
    /**
     * 
     * @param string $file_id
     * @param array $data 
     * @return $this | ClientError | Boolean
     */
    public function copy_file(string $file_id, array $data = [])
    {
        if (!isset($file_id)) {
            $error = new ClientError();
            $error->status = '400';
            $error->message = 'Failed to pass file_id to function';
            return $error;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/copy';
        $params = [
            ':file_id' => $file_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                /**
                 * Returns a collection of files, folders, and web links contained in a folder.
                 */
                $file = new File($this->token);
                $file->hydrate($this->response);
                return $file;
            case 304:
                /**
                 * Returns an empty response when the If-None-Match header matches the current etag value of the file. This indicates that the file has not changed since it was last requested.
                 */
                return null;
            case 400:
                /**
                 * Returns an error if some of the parameters are missing or not valid.
                 */
            case 403:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            case 404:
                /**
                 * Returned if the folder is not found, or the user does not have access to the folder.
                 */
            case 409:
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
     * Updates a file. This can be used to rename or move a file, create a shared link, or lock a file.
     * @param string $file_id
     * @param array $data
     * @return $this | ClientError
     */
    public function update_file(string $file_id, array $data = [])
    {
        if (!isset($file_id)) {
            $error = new ClientError();
            $error->status = '400';
            $error->message = 'Failed to pass file_id to function';
            return $error;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/copy';
        $params = [
            ':file_id' => $file_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->put($uri, $data);
        
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a file object.
                 */
                $file = new File($this->token);
                $file->hydrate($this->response);
                return $file;
            case 400:
                /**
                 * Returned when the new retention time > maximum retention length.
                 */
            case 401:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            case 403:
                /**
                 * Returned if the user does not have all the permissions to complete the update.
                 * access_denied_insufficient_permissions returned when the authenticated user does not have access to the destination folder to move the file to.
                 * Returned when retention time is shorter or equal to current retention timestamp.
                 * Returned when a file_id that is not under retention is entered.
                 * Returned when a file that is retained but the disposition action is set to remove_retention
                 * forbidden_by_policy is returned if copying a folder is forbidden due to information barrier restrictions.
                 */
            case 404:
                /**
                 * Returned if the file is not found, or the user does not have access to the file.
                 */
            case 405:
                /**
                 * Returned if the file_id is not in a recognized format.
                 */
            case 412:
                /**
                 * Returns an error when the If-Match header does not match the current etag value of the file. This indicates that the file has changed since it was last requested.
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
    
    public function delete_file(string $file_id)
    {
        if (!isset($file_id)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id';
        $params = [
            ':file_id' => $file_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 204:
                /**
                 * Returns an empty response when the file has been successfully deleted.
                 */
                return;
            case 401:
                /**
                 * Returned when the access token provided in the Authorization header is not recognized or not provided.
                 */
            case 404:
                /**
                 * Returned if the file is not found or has already been deleted, or the user does not have access to the file.
                 */
            case 405:
                /**
                 * Returned if the file_id is not in a recognized format.
                 */
            case 412:
                /**
                 * Returns an error when the If-Match header does not match the current etag value of the file. This indicates that the file has changed since it was last requested.
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
     * Returns the contents of a file in binary format.
     */
    public function download_file(string $file_id = null, array $query = null)
    {
        if (!isset($file_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/content';
        
        $params = [
            ':file_id' => $file_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * 
                 */
                return $this->getResponse();
            case 202:
                /**
                 * If the file is not ready to be downloaded yet Retry-After header will be returned indicating the time in seconds after which the file will be available for the client to download.
                 * This response can occur when the file was uploaded immediately before the download request.
                 */
                
            case 302:
                /**
                 * If the file is available to be downloaded the response will include a Location header for the file on dl.boxcloud.com.
                 * The dl.boxcloud.com URL is not persistent and clients will need to follow the redirect in order to actually download the file.
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
    
    public function download_file_representation()
    {
        /**
         * If representation doesn't exist, return a blank response.
         */
        if (!isset($this->representations['entries'][0])) {
            return new Response();
        }
        
        $this->headers->clearHeaders();
        $endpoint = $this->representations['entries'][0]['content']['url_template'];
        
        $params = [
            '{+asset_path}' => "",
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                return $this->getResponse();
            case 202:
                throw new \Exception('202 Retry Error');
            default:
                /**
                 * An unexpected client error.
                 */
                $error = new ClientError();
                $error->hydrate($this->getResponse());
                return $error;
        }
    }
    
    public function request_desired_representation(string $type = null, string $size = null)
    {
        $this->headers->clearHeaders();
        $properties = sprintf('[%s?dimensions=%s]', $type, $size);
        $this->headers->addHeaderLine('x-rep-hints',$properties);
        return $this->get_file_information($this->id);
    }

    /**
     * Utilizes update_file API Call to rename file.
     * @param string $file_id
     * @param string $name
     * @return \Laminas\Box\API\Resource\File
     */
    public function rename_file(string $file_id, string $name)
    {
        if (!isset($file_id) || !isset($name)) {
            throw new ClientErrorException('file_id or name not present in rename_file().');
        }
        
        $data = [
            'name' => $name,
        ];
        
        return $this->update_file($file_id, $data);
    }
    
    /**
     * Utilizes update_file API Call to move file to a new folder.
     * @param string $file_id
     * @param string $folder_id
     * @return \Laminas\Box\API\Resource\File
     */
    public function move_file(string $file_id, string $folder_id)
    {
        if (!isset($file_id) || !isset($folder_id)) {
            throw new ClientErrorException('file_id or folder_id not present in move_file().');
        }
        
        $data = [
            'parent' => [
                'id' => $folder_id,
            ],
        ];
        
        return $this->update_file($file_id, $data);
    }
}