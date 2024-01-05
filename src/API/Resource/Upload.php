<?php
namespace Laminas\Box\API\Resource;

use Laminas\Http\Response;
use Exception;

class Upload extends File
{
    /**
     * 
     * @var string
     */
    protected $content_type = 'multipart/form-data';
    
    /**
     * 
     * @var array
     */
    public $attributes;
    
    /**
     * 
     * @var string
     */
    public $file;
    
    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Specify the Folder ID to put new file.
     * Properties for file should be populated prior to calling this function.
     * 
     * @param array $attributes
     * @param string $file
     * @return \Laminas\Box\API\Resource\Files
     */
    public function upload_file(array $attributes, string $file)
    {
        if (!isset($attributes) || !isset($file)) {
            return false;
        }
        
        $endpoint = 'https://upload.box.com/api/2.0/files/content';
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        
        $data = [
            'attributes' => json_encode($attributes),
            'file' => new \CURLFile($file, mime_content_type($file), $attributes['name']),
        ];
        
        $this->response = $this->post($uri, $data);
        
        
        switch ($this->response->getStatusCode()) {
            case 201:
                /**
                 * Returns the new file object in a list.
                 */
                $json = $this->response->getContent();
                $ary = json_decode($json, true);
        
                $files = new Files($this->token);
                foreach ($ary['entries'] as $key => $entry) {
                    $file = new File($this->token);
                    $file->hydrate($entry);
                    $files->entries[$key] = $file;
                }
                return $files;
            case 400:
                /**
                 * Returns an error if some of the parameters are missing or not valid.
                 *     bad_request          when a parameter is missing or incorrect.
                 *     item_name_too_long   when the folder name is too long.
                 *     item_name_invalid    when the folder name contains non-valid characters.
                 */
            case 409:
                /**
                 * Returns an error if the file already exists, or the account has run out of disk space.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Overrides Parent
     * 
     * @see \Laminas\Box\API\Resource\AbstractResource::post()
     */
    protected function post (string $uri, array $data)
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $uri,
                CURLOPT_HEADER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->token,
                    'Content-Type: multipart/form-data',
                    'Expect:',
                ],
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            
            
            $result = curl_exec($ch);
            
            $response = new Response();
            $this->response = $response->fromString($result);
            
            curl_close($ch);
        } catch (Exception $e) {
            $this->response = $e->getMessage();
        }
        
        return $this->response;
    }
}