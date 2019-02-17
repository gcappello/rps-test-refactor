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
namespace Game\Application\Repository\User;

/**
 * Represents a persistent set of users.
 */
interface UserRepositoryInterface
{
    /**
     * Retrieves user by his/her name.
     *
     * @param string $name
     * @return \Game\Application\Repository\User\UserInterface|null Repository matching the given name, or null if not found
     */
    public function findByName(string $name): ?UserInterface;

    /**
     * Retrieves user by username:password combination.
     *
     * @param string $name
     * @param string $password
     * @return \Game\Application\Repository\User\UserInterface|null Repository matching the given name and password, or null if not found
     */
    public function findByLogin(string $name, string $password): ?UserInterface;

    /**
     * Registers the given user into this repository.
     *
     * @param \Game\Application\Repository\User\UserInterface $user
     * @return bool
     */
    public function save(UserInterface $user): bool;
}
