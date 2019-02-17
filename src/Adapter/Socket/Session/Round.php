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
namespace Game\Adapter\Socket\Session;

use Game\Application\Session\RoundInterface;
use Game\Application\Shape\ShapeHandlerInterface;

/**
 * Represents a single round within a game match.
 *
 * @package \Game\Adapter\Socket\Session
 */
class Round implements RoundInterface
{
    protected $playerShape;
    protected $serverShape;
    protected $shapeHandler;

    public function __construct(ShapeHandlerInterface $shapeHandler)
    {
        $this->shapeHandler = $shapeHandler;
    }

    public function getPlayerShape(): string
    {
        return $this->playerShape;
    }

    public function setPlayerShape(string $shape): void
    {
        $this->playerShape = $shape;
    }

    public function getServerShape(): string
    {
        return $this->serverShape;
    }

    public function setServerShape(string $shape): void
    {
        $this->serverShape = $shape;
    }

    public function isTie(): bool
    {
        return $this->shapeHandler->compare($this->getPlayerShape(), $this->getServerShape()) === 0;
    }

    public function isPlayerWinner(): bool
    {
        return $this->shapeHandler->compare($this->getPlayerShape(), $this->getServerShape()) === 1;
    }

    public function isServerWinner(): bool
    {
        return $this->shapeHandler->compare($this->getPlayerShape(), $this->getServerShape()) === -1;
    }

    public function resultForPlayer(): string
    {
        if ($this->isTie()) {
            return 'tie';
        } elseif ($this->isPlayerWinner()) {
            return 'win';
        }

        return 'lose';
    }

    public function resultForServer(): string
    {
        if ($this->isTie()) {
            return 'tie';
        } elseif ($this->isServerWinner()) {
            return 'win';
        }

        return 'lose';
    }
}
