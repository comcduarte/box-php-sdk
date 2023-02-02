<?php
namespace Laminas\Box\API\Resource;

use Exception;
use Laminas\Http\Response;
use Laminas\Box\API\AccessToken;

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
     * @return Upload
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
     * @return Upload
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
        $endpoint = 'https://upload.box.com/api/2.0/files/content';
        $params = [
            //-- No Parameters are required. --//
        ];
        $uri = strtr($endpoint, $params);
        
        $data = [
            'attributes' => json_encode($attributes),
            'file' => new \CURLFile($file, mime_content_type($file), $attributes['name']),
        ];
        
        $response = $this->post($uri, $data);
        $json = $response->getContent();
        $ary = json_decode($json, true);
        
        /**
         * @todo If error, do not return files, return error
         */
        
        /**
         * Prepare Return Value.
         * 
         * @var \Laminas\Box\API\Resource\Files $files
         */
        $files = new Files(new AccessToken([]));
        foreach ($ary['entries'] as $key => $entry) {
            $file = new File($this->token);
            $file->hydrate($entry);
            $files->entries[$key] = $file;
        }
        return $files;
    }

    //-- should be the same as resouce::post --//
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
//                 CURLOPT_INFILESIZE => filesize($data['file']),
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