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
namespace Game\Application\Session;

/**
 * Represents a session round.
 */
interface RoundInterface
{
    /**
     * Gets the shape given by player during this round.
     *
     * @return string
     */
    public function getPlayerShape(): string;

    /**
     * Sets player shape choice.
     *
     * @param string $shape
     * @return void
     */
    public function setPlayerShape(string $shape): void;

    /**
     * Gets the shape picked by server during this round.
     *
     * @return string
     */
    public function getServerShape(): string;

    /**
     * Sets server shape choice.
     *
     * @param string $shape
     * @return void
     */
    public function setServerShape(string $shape): void;

    /**
     * Whether this round was a tie.
     *
     * Equivalent to (!isPLayerWinner() && !isServerWinner())
     *
     * @return bool
     */
    public function isTie(): bool;

    /**
     * Whether this round was won by human player.
     *
     * @return bool
     */
    public function isPlayerWinner(): bool;

    /**
     * Whether this round was won by the server.
     *
     * @return bool
     */
    public function isServerWinner(): bool;

    /**
     * Round's result from human player's point of view.
     *
     * @return string Possible values are: `win`, `lose` or `tie`
     */
    public function resultForPlayer(): string;

    /**
     * Round's result from server's point of view.
     *
     * @return string Possible values are: `win`, `lose` or `tie`
     */
    public function resultForServer(): string;
}
