<?php
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\ResourceType;

class User extends AbstractResource
{
    public ResourceType $type = ResourceType::User;

    public string $address;

    public string $avatar_url;

    public bool $can_see_managed_users;

    public string $created_at;

    public BaseResource $enterprise;

    public string $external_app_user_id;

    public string $hostname;

    public bool $is_exempt_from_device_limits;

    public bool $is_exempt_from_login_verification;

    public bool $is_platform_access_only;

    public bool $is_sync_enabled;

    public string $job_title;

    public string $language;

    public string $login;

    public int $max_upload_size;

    public string $modified_at;

    public array $my_tags;

    public string $name;

    /**
     *
     * @todo Convert into a Resource
     * @var NotificationEmail
     */
    public \stdClass $notification_email;

    public string $phone;

    public string $role;

    public int $space_amount;

    public int $space_used;

    public string $status;

    public string $timezone;

    public \stdClass $tracking_codes;
    
    /**
     * Returns a list of all users for the Enterprise along with their user_id, public_name, and login.
     * The application and the authenticated user need to have the permission to look up users in the entire enterprise.
     * 
     * @param
     * @return Users|ClientError
     */
    public function list_enterprise_users()
    {
        $endpoint = 'https://api.box.com/2.0/users';
        $params = [
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns all of the users in the enterprise.
                 */
                $users = new Users();
                $users->hydrate($this->response);
                return $users;
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
    
    /**
     * Retrieves information about a user in the enterprise.
     * The application and the authenticated user need to have the permission to look up users in the entire enterprise.
     * This endpoint also returns a limited set of information for external users who are collaborated on content owned by the enterprise for authenticated users with the right scopes. In this case, disallowed fields will return null instead.
     * 
     * @param string $user_id
     * @return User|ClientError
     */
    public function get_user(string $user_id = null)
    {
        if (!isset($user_id)) {
            return false;
        }
        
        $endpoint = 'https://api.box.com/2.0/users/:user_id';
        $params = [
            ':user_id' => $user_id,
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a single user object.
                 * Not all available fields are returned by default. Use the fields query parameter to explicitly request any specific fields using the fields parameter.
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

    /**
     * Retrieves information about the user who is currently authenticated.
     * In the case of a client-side authenticated OAuth 2.0 application this will be the user who authorized the app.
     * In the case of a JWT, server-side authenticated application this will be the service account that belongs to the application by default.
     * Use the As-User header to change who this API call is made on behalf of.
     * 
     * @return User|ClientError
     */
    public function get_current_user()
    {
        $endpoint = 'https://api.box.com/2.0/users/me';
        $params = [
        ];
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns a single user object.
                 * Not all available fields are returned by default. Use the fields query parameter to explicitly request any specific fields using the fields parameter.
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


}