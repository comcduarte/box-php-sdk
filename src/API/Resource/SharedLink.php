<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\Access;
use DateTime;

class SharedLink
{
    use HydrationTrait;

    public Access $access;

    public ?string $password = null;

    /**
     * Minimum string length: 12
     *
     * @var unknown
     */
    public ?string $vainty_name = null;

    public DateTime $unshared_at;

    public Permissions $permissions;

    public string $url;

    public Access $effective_access;

    public Permissions $effective_permission;

    public bool $is_password_enabled;

    public int $download_count;

    public int $preview_count;

    public function __construct()
    {
        $datetime = new DateTime();
        $datetime->modify('+1 week');

        $this->access = Access::Open;
        $this->unshared_at = $datetime;

        $this->permissions = new Permissions();
    }

    /**
     *
     * @return Access
     */
    public function getAccess()
    {
        return $this->access->value;
    }

    /**
     *
     * @param Access $access
     */
    public function setAccess($access): self
    {
        $this->access = Access::from($access);
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     *
     * @return \comcduarte\Box\API\Resource\unknown
     */
    public function getVaintyName()
    {
        return $this->vainty_name;
    }

    /**
     *
     * @param \comcduarte\Box\API\Resource\unknown $vainty_name
     */
    public function setVaintyName($vainty_name)
    {
        $this->vainty_name = $vainty_name;
        return $this;
    }

    /**
     *
     * @return DateTime
     */
    public function getUnsharedAt()
    {
        return $this->unshared_at;
    }

    /**
     *
     * @param DateTime $unshare_at
     */
    public function setUnsharedAt($unshared_at)
    {
        if ($unshared_at instanceof DateTime) {
            $this->unshared_at = $unshared_at;
        } else {
            $this->unshared_at = DateTime::createFromFormat(DateTime::ISO8601, $unshared_at);
        }
        return $this;
    }

    /**
     *
     * @return \comcduarte\Box\API\Resource\Permissions
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     *
     * @param \comcduarte\Box\API\Resource\Permissions $permissions
     */
    public function setPermissions($permissions)
    {
        if ($permissions instanceof Permissions) {
            $this->permissions = $permissions;
        } else {
            $this->permissions = new permissions();
            $this->permissions->hydrate($permissions);
        }
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     *
     * @return \comcduarte\Box\API\Enum\Access
     */
    public function getEffectiveAccess()
    {
        return $this->effective_access;
    }

    /**
     *
     * @param \comcduarte\Box\API\Enum\Access $effective_access
     */
    public function setEffectiveAccess($effective_access)
    {
        $this->effective_access = Access::from($effective_access);
        return $this;
    }

    /**
     *
     * @return \comcduarte\Box\API\Resource\Permissions
     */
    public function getEffectivePermission()
    {
        return $this->effective_permission;
    }

    /**
     *
     * @param \comcduarte\Box\API\Resource\Permissions $effective_permission
     */
    public function setEffectivePermission($effective_permission)
    {
//         if ($effective_permission instanceof Permissions) {
//             $this->effective_permission = $effective_permission;
//         } else {
//             $this->effective_permission = new Permissions();
//             $this->effective_permission->hydrate($effective_permission);
//         }
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isIsPasswordEnabled()
    {
        return $this->is_password_enabled;
    }

    /**
     *
     * @param boolean $is_password_enabled
     */
    public function setIsPasswordEnabled($is_password_enabled)
    {
        $this->is_password_enabled = $is_password_enabled;
        return $this;
    }

    /**
     *
     * @return number
     */
    public function getDownloadCount()
    {
        return $this->download_count;
    }

    /**
     *
     * @param number $download_count
     */
    public function setDownloadCount($download_count)
    {
        $this->download_count = $download_count;
        return $this;
    }

    /**
     *
     * @return number
     */
    public function getPreviewCount()
    {
        return $this->preview_count;
    }

    /**
     *
     * @param number $preview_count
     */
    public function setPreviewCount($preview_count)
    {
        $this->preview_count = $preview_count;
        return $this;
    }
}