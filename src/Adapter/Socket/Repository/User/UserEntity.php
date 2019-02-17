<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Game\Adapter\Socket\Repository\User;

use Game\Application\Repository\User\UserInterface;

/**
 * Represents a single user.
 *
 * @package \Game\Adapter\Socket\Repository\User
 */
class UserEntity implements UserInterface
{
    /**
     * Repository's name.
     *
     * @var string
     */
    protected $name;

    /**
     * Repository's password..
     *
     * @var string
     */
    protected $password;

    /**
     * Last seen.
     *
     * @var \DateTime
     */
    protected $lastSeen;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getLastSeen(): \DateTime
    {
        return $this->lastSeen;
    }

    /**
     * @inheritDoc
     */
    public function setLastSeen(\DateTime $dateTime): void
    {
        $this->lastSeen = $dateTime;
    }

    /**
     * Re-builds an object using persisted data.
     *
     * @param array $attributes
     *
     * @return \Game\Adapter\Socket\Repository\User\UserEntity
     */
    public static function __set_state($attributes)
    {
        $user = new self();
        $user->setName($attributes['name']);
        $user->setPassword($attributes['password']);
        $user->setLastSeen($attributes['lastSeen']);

        return $user;
    }
}
