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
 * Represents a user of our service.
 */
interface UserInterface
{

    /**
     * Gets user's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets user's name.
     *
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * Gets user's password.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Sets user's password.
     *
     * @param string $password
     */
    public function setPassword(string $password): void;

    /**
     * When user was last seen.
     *
     * @return \DateTime
     */
    public function getLastSeen(): \DateTime;

    /**
     * Sets last seen time.
     *
     * @param \DateTime $dateTime
     */
    public function setLastSeen(\DateTime $dateTime): void;
}
