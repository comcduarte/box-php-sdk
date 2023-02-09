<?php
namespace Laminas\Box\API\Resource;

use Laminas\Stdlib\ArraySerializableInterface;

class File extends AbstractResource implements ArraySerializableInterface
{
    use HydrationTrait;
    
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
     * @var string|mixed
     */
    
    
    public function get_file_information(string $id)
    {
        $endpoint = 'https://api.box.com/2.0/files/:file_id';
        $params = [
            ':file_id' => $id,
        ];
        $uri = strtr($endpoint, $params);
        
        $this->response = $this->get($uri);
    }
    
    public function get_file_thumbnail() {}
    
    public function copy_file(string $file_id, $data)
    {
        if (!isset($file_id) | !isset($data)) {
            return FALSE;
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/copy';
        $params = [
            ':file_id' => $file_id,
        ];
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
    }
    
    public function update_file() {}
    
    public function delete_file() {}
    
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
        
        $query = ['access_token' => $this->token];
        
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
                $error->hydrate($this->response);
                return $error;
        }
    }
    
    public function exchangeArray(array $array)
    {
        foreach (array_keys(get_object_vars($this)) as $var) {
            if (!empty($array[$var])) {
                $this->$var = $array[$var];
            }
        }
    }
    
    public function getArrayCopy()
    {
        $data = [];
        foreach (array_keys(get_object_vars($this)) as $var) {
            $data[$var] = $this->{$var};
        }
        return $data;
    }
}