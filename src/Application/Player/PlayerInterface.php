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
namespace Game\Application\Player;

/**
 * Represents a game player within the system.
 */
interface PlayerInterface
{
    /**
     * Whether this player is registered or not.
     *
     * @return bool
     */
    public function isRegistered(): bool;

    /**
     * Mark this player as registered.
     *
     * @param bool $registered
     */
    public function setRegistered(bool $registered);

    /**
     * Gets player's IO handler.
     * @return \Game\Application\Player\IOInterface
     */
    public function getIO(): IOInterface;

    /**
     * Sets player's connection.
     *
     * @param \Game\Application\Player\IOInterface $io IO handler instance
     * @return void
     */
    public function setIO(IOInterface $io): void;

    /**
     * Gets player's name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets player's name.
     *
     * @param string $name Player's name
     * @return void
     */
    public function setName(string $name): void;
}
