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

use Game\Application\Player\PlayerInterface;
use Game\Application\Shape\ShapeHandlerInterface;

/**
 * Represents game session, human vs server.
 */
interface GameInterface
{

    /**
     * Gets player.
     *
     * @return \Game\Application\Player\PlayerInterface
     */
    public function getPlayer(): ?PlayerInterface;

    /**
     * Registers the given player into this session.
     *
     * @param \Game\Application\Player\PlayerInterface $player New player
     * @return void
     */
    public function setPlayer(PlayerInterface $player): void;

    /**
     * Gets session rounds.
     *
     * @return int
     */
    public function getMaxRounds(): ?int;

    /**
     * Set session rounds.
     *
     * @param int $rounds Rounds number (>0)
     * @return void
     */
    public function setMaxRounds(int $rounds): void;

    /**
     * Gets the round collection attached to this game session.
     */
    public function getRoundCollection(): ?RoundCollection;

    /**
     * Attaches a round collection to this game session.
     *
     * @param \Game\Application\Session\RoundCollection $rounds
     * @return void
     */
    public function setRoundCollection(RoundCollection $rounds): void;

    /**
     * Gets session's shape handler.
     *
     * @return \Game\Application\Shape\ShapeHandlerInterface
     */
    public function getShapeHandler(): ?ShapeHandlerInterface;

    /**
     * Registers shape handle for this session.
     *
     * @param \Game\Application\Shape\ShapeHandlerInterface $shapeHandler Handler instance
     * @return void
     */
    public function setShapeHandler(ShapeHandlerInterface $shapeHandler): void;

    /**
     * Whether this game session is ready to start.
     *
     * @return bool True if ready, False otherwise
     */
    public function isReady(): bool;

    /**
     * Starts playing.
     *
     * @param array $options Additional options used to control the game while running
     * @throws \Game\Application\Session\Error\SessionNotReadyException When session is not ready to start
     * @return \Game\Application\Session\RoundCollection
     */
    public function play(array $options = []): RoundCollection;
}
