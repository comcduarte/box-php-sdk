<?php
namespace Laminas\Box\API\Resource;

use Laminas\Json\Json;

class UploadSession extends AbstractResource
{
    /**
     * 
     * @var string
     */
    protected $content_type = 'application/octet-stream';
    
    public $id;
    public $type;
    public $num_parts_processed;
    public $part_size;
    public $session_endpoints;
    public $session_expires_at;
    public $total_parts;
    
    public $file_name;
    public $file_size;
    public $folder_id;
    
    /**
     * The byte range of the chunk.
     * Must not overlap with the range of a part already uploaded this session.
     * 
     * @var string
     */
    public $content_range;
    
    /**
     * The RFC3230 message digest of the chunk uploaded.
     * Only SHA1 is supported. The SHA1 digest must be Base64 encoded. 
     * The format of this header is as sha=BASE64_ENCODED_DIGEST.
     * 
     * @var string
     */
    public $digest;
    
    /**
     * The binary content of the file.
     * @var mixed
     */
    public $binary;
    
    public function list_upload_session(string $upload_session_id)
    {
        if (!isset($upload_session_id)) {
            return FALSE;
        }
        
        $this->content_type = 'application/json';
        $endpoint = 'https://upload.box.com/api/2.0/files/upload_sessions/:upload_session_id';
        $params = [
            ':upload_session_id' => $upload_session_id,
        ];
        $uri = strtr($endpoint, $params);
        
        $response = $this->get($uri);
        return $response;
    }
    
    public function create_upload_session(string $file_name, int $file_size, string $folder_id)
    {
        if (!isset($file_name) | !isset($file_size) | !isset($folder_id)) {
            return FALSE;
        }
        
        $this->content_type = 'application/json';
        $endpoint = 'https://upload.box.com/api/2.0/files/upload_sessions';
        $params = [
            //-- No Parameters are required. --//
        ];
        $uri = strtr($endpoint, $params);
        
        $data = [
            'folder_id' => $folder_id,
            'file_size' => $file_size,
            'file_name' => $file_name,
        ];
        
        $response = $this->post($uri, $data);
        
        switch ($response->getStatusCode())
        {
            case 200:
            case 201:
                //-- OK --//
                $a = Json::decode($response->getContent());
                $this->id = $a->id;
                $this->type = $a->type;
                $this->num_parts_processed = $a->num_parts_processed;
                $this->part_size = $a->part_size;
                $this->session_expires_at = $a->session_expires_at;
                $this->total_parts = $a->total_parts;
                return true;
            case 400:
                //-- Bad Request --//
                return false;
            default:
                return false;
        }
        
        return $response;
    }
    
    public function commit_upload_session(string $upload_session_id)
    {
        if (!isset($upload_session_id)) {
            return FALSE;
        }
        
        $endpoint = 'https://upload.box.com/api/2.0/files/:upload_session_id/commit';
        $params = [
            ':upload_session_id' => $upload_session_id,
        ];
        $uri = strtr($endpoint, $params);
        
        $parts = [];
        
        $response = $this->post($uri, $parts);
        return $response;
    }
    
    /**
     * Updates a chunk of an upload session for a file.
     * Each part’s size must be exactly equal in size to the part size specified in the upload session that was created. 
     * One exception is the last part of the file, as this is allowed to be smaller.
     * 
     * @param string $upload_session_id
     * @return boolean|\Laminas\Http\Response
     */
    public function upload_part_of_file(int $start, int $end, $binary)
    {
        $this->content_type = 'application/octet-stream';
        
        if (!$this->getId()) {
            return FALSE;
        }
        
        $endpoint = 'https://upload.box.com/api/2.0/files/upload_sessions/:upload_session_id';
        $params = [
            ':upload_session_id' => $this->id,
        ];
        $uri = strtr($endpoint, $params);
        
        $this->headers->addHeaderLine(sprintf('Digest: sha=%s', sha1_file($this->file_name, true)));
        $this->headers->addHeaderLine(sprintf('Content-Range: bytes %s-%s/%s', $start, $end, $this->file_size));
        
        $response = $this->put($uri, $binary);
        return $response;
    }
    
    public function upload_file($file_name, $file_size)
    {
        $start = 0;
        $end = $this->part_size;
        
        $handle = fopen($file_name, 'rb');
        while(!feof($handle)) {
            $binary = fread($handle, $this->part_size);
            
            $start = ($start + $this->part_size + 1);
            $end = ($end + $this->part_size + 1);
            if ($end > $file_size) {
                $end = $file_size;
                $this->part_size = $end - $start;
            }
            $this->response = $this->upload_part_of_file($start, $end, $binary);
        }
        fclose($handle);
        
        return $$this->response;
    }
    
    public function remove_upload_session()
    {
        if (!$this->getId()) {
            return FALSE;
        }
        
        $this->content_type = 'application/json';
        $endpoint = 'https://upload.box.com/api/2.0/files/upload_sessions/:upload_session_id';
        $params = [
            ':upload_session_id' => $this->getId(),
        ];
        $uri = strtr($endpoint, $params);
        
        $response = $this->delete($uri);
        return $response;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}