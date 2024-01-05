<?php
namespace Laminas\Box\API\Resource;

use Laminas\Box\API\AccessToken;
use Laminas\Http\Client;
use Laminas\Http\Headers;
use Laminas\Http\Response;
use Laminas\Http\Client\Adapter\Curl;
use Laminas\Hydrator\ArraySerializableHydrator;

abstract class AbstractResource
{
    /**#@+
     *
     * @const string METHOD constant names
     */
    public const METHOD_OPTIONS  = 'OPTIONS';
    public const METHOD_GET      = 'GET';
    public const METHOD_HEAD     = 'HEAD';
    public const METHOD_POST     = 'POST';
    public const METHOD_PUT      = 'PUT';
    public const METHOD_DELETE   = 'DELETE';
    public const METHOD_TRACE    = 'TRACE';
    public const METHOD_CONNECT  = 'CONNECT';
    public const METHOD_PATCH    = 'PATCH';
    public const METHOD_PROPFIND = 'PROPFIND';
    /**#@-*/
    
    /**
     * Stores the access token string to be passed in Authorization Header.
     * @var string
     */
    protected $token;
    
    /**
     *
     * @var string
     */
    protected $content_type = 'application/json';
    
    /**
     *
     * @var boolean
     */
    protected $requires_authorization = true;
    
    /**
     *
     * @var Headers
     */
    protected $headers;
    
    /**
     *
     * @var Response
     */
    protected $response;
    
    /**
     *
     * @var string
     */
    protected $uri;
    
    public function __construct($access_token = null)
    {
        if ($this->requires_authorization) {
            if (is_object($access_token) & is_a($access_token, AccessToken::class)) {
                $this->token = $access_token->getAccessToken();
            } elseif (is_string($access_token)) {
                $this->token = $access_token;
            } else {
                throw new \Exception('Invalid Access Token');
            }
        }
        
        $this->headers = new Headers();
        return $this;
    }
    
    protected function delete(string $uri)
    {
        $this->add_authorization();
        $this->add_content_type();
        
        $client = new Client();
        $client->setOptions([
            'sslverifypeer' => FALSE,
        ]);
        $client->setAdapter(new Curl());
        $client->setUri($uri);
        $client->setMethod(self::METHOD_DELETE);
        $client->setHeaders($this->headers);
        
        $response = $client->send();
        
        return $response;
    }
    
    protected function get(string $uri)
    {
        $this->add_authorization();
        
        $client = new Client();
        $client->setOptions([
            'sslverifypeer' => FALSE,
        ]);
        $client->setAdapter(new Curl());
        $client->setUri($uri);
        $client->setMethod(self::METHOD_GET);
        $client->setHeaders($this->headers);
        $response = $client->send();
        
        return $response;
    }
    
    /**
     * Used for Post and Put cURL Requests
     * @param string $uri
     * @param array $data
     */
    protected function send(string $uri, array $data, string $method)
    {
        $this->add_authorization();
        $this->add_content_type();
        
        $client = new Client();
        $client->setOptions([
            'sslverifypeer' => FALSE,
        ]);
        $client->setAdapter(new Curl());
        $client->setUri($uri);
        
        /**
         * $method is PUT or POST based on wrapper.
         */
        $client->setMethod($method);
        $client->setHeaders($this->headers);
        
        switch ($this->content_type) {
            case 'application/json':
                $client->setRawBody(json_encode($data));
                break;
            case 'application/x-www-form-urlencoded':
                $post = [];
                foreach ($data as $param => $value) {
                    if (isset($value)) {
                        $post[$param] = $value;
                    }
                }
                $client->setParameterPost($post);
                break;
            default:
                /**
                 * @TODO Send data to client
                 */
                $post = $data;
                break;
        }
        
        $this->response = $client->send();
        return $this->response;
    }
    
    /**
     * 
     * @param string $uri
     * @param array $data
     * @return \Laminas\Http\Response
     */
    protected function put(string $uri, array $data)
    {
        return $this->send($uri, $data, self::METHOD_PUT);
    }
    
    /**
     * 
     * @param string $uri
     * @param array $data
     * @return \Laminas\Http\Response
     */
    protected function post(string $uri, array $data)
    {
        return $this->send($uri, $data, self::METHOD_POST);
    }
    
    private function add_authorization()
    {
        if ($this->requires_authorization == TRUE) {
            $this->headers->addHeaderLine(sprintf('Authorization: Bearer %s', $this->token));
        }
        return $this;
    }
    
    private function add_content_type()
    {
        if (isset($this->content_type)) {
            $this->headers->addHeaderLine('Content-Type', $this->content_type);
        }
        return $this;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Generates URI
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    public function generate_uri(string $endpoint, array $params)
    {
        if (isset($this->query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($this->query as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        return strtr($endpoint, $params);
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
    
    public function hydrate($response)
    {
        $hydrator = new ArraySerializableHydrator();
        
        if (is_a($response, Response::class)) {
            $data = json_decode($response->getContent(), true);
            $hydrator->hydrate($data, $this);
        } elseif (is_array($response)) {
            $hydrator->hydrate($response, $this);
        } else {
            throw new \Exception('Invalid parameter in hydrate function.  Must be of type array or Response.');
        }
        return $this;
    }

    public function error()
    {
        $error = new ClientError();
        $error->hydrate($this->response);
        return $error;
    }
}