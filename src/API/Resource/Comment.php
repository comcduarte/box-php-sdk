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
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \comcduarte\Box\API\Enum\ResourceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return \comcduarte\Box\API\Resource\User
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @return boolean
     */
    public function isReplyComment()
    {
        return $this->is_reply_comment;
    }

    /**
     * @return \comcduarte\Box\API\Resource\BaseResource
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @return string
     */
    public function getTaggedMessage()
    {
        return $this->tagged_message;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param \comcduarte\Box\API\Enum\ResourceType $type
     */
    public function setType($type)
    {
        $this->type = ResourceType::from($type);
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @param \comcduarte\Box\API\Resource\User $created_by
     */
    public function setCreatedBy($created_by)
    {
        if ($created_by instanceof User) {
            $this->created_by = $created_by;
        } else {
            $this->created_by = new User($this->token);
            $this->created_by->hydrate($created_by);
        }
        return $this;
    }

    /**
     * @param boolean $is_reply_comment
     */
    public function setIsReplyComment($is_reply_comment)
    {
        $this->is_reply_comment = $is_reply_comment;
    }

    /**
     * @param \comcduarte\Box\API\Resource\BaseResource $item
     */
    public function setItem($item)
    {
        if ($item instanceof BaseResource) {
            $this->item = $item;
        } else {
            $this->item = new BaseResource($this->token);
            $this->item->hydrate($item);
        }
        return $this;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param string $modified_at
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
    }

    /**
     * @param string $tagged_message
     */
    public function setTaggedMessage($tagged_message)
    {
        $this->tagged_message = $tagged_message;
    }

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

    public function create_comment(string $message, BaseResource $item, Query $query = null): self|ClientError
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
                'id' => $item->getId(),
                'type' => $item->getType(),
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