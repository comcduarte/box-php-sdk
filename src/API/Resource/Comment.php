<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\ResourceType;
use comcduarte\Box\API\Exception\ClientErrorException;

class Comment extends AbstractResource
{

    public string $id;

    public ResourceType $type = ResourceType::Comment;

    public string $created_at;

    public User $created_by;

    public bool $is_reply_comment;

    public BaseResource $item;

    public string $message;

    public string $modified_at;

    public string $tagged_message;
    
    public function list_file_comments(string $file_id, Query $query = null): Comments|ClientError
    {
        if (!isset($file_id)) {
            throw new ClientErrorException('file_id not set.');
        }
        
        $endpoint = 'https://api.box.com/2.0/files/:file_id/comments';
        $params = [
            ':file_id' => $file_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query->getArrayCopy() as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a collection of comment objects. If there are no comments on this file an empty collection will be returned.
                 */
                $comments = new Comments();
                $comments->hydrate($this->response);
                return $comments;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function get_comment(string $comment_id, Query $query = null): self|ClientError
    {
        if (!isset($comment_id)) {
            throw new ClientErrorException('comment_id not set.');
        }
        
        $endpoint = 'https://api.box.com/2.0/comments/:comment_id';
        $params = [
            ':comment_id' => $comment_id,
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query->getArrayCopy() as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a full comment object.
                 */
                $this->hydrate($this->response);
                return $this;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function create_comment(string $message, BaseResource $item, Query $query): self|ClientError
    {
        if (!isset($message) || !isset($item)) {
            throw new ClientErrorException('Parameters not set.');
        }
        
        $endpoint = 'https://api.box.com/2.0/comments';
        $params = [
            //-- No Parameters are required. --//
        ];
        
        $data = [
            'message' => $message,
            'item' => [
                'id' => $item->id,
                'type' => $item->type,
            ],
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query->getArrayCopy() as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->post($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 201:
                /**
                 * Returns the newly created comment object.
                 */
                $this->hydrate($this->response);
                return $this;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function update_comment(string $comment_id, string $message): self|ClientError 
    {
        $endpoint = 'https://api.box.com/2.0/comments/:comment_id';
        $params = [
            ':comment_id' => $comment_id,
        ];
        
        $data = [
            'message' => $message,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->put($uri, $data);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns the updated comment object.
                 */
                $this->hydrate($this->response);
                return $this;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }

    public function remove_comment(string $comment_id): bool|ClientError
    {
        if (!isset($comment_id)) {
            throw new ClientErrorException('Parameters not set.');
        }
        
        $endpoint = 'https://api.box.com/2.0/comments/:comment_id';
        $params = [
            ':comment_id' => $comment_id,
        ];
        
        $uri = strtr($endpoint, $params);
        $this->response = $this->delete($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 204:
                /**
                 * Returns an empty response when the comment has been deleted.
                 */
                return true;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
}