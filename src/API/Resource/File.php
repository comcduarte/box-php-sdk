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