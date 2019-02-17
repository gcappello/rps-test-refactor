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
namespace Game\Adapter\Socket\Player;

use Game\Application\Player\IOInterface;
use Game\Application\Player\PlayerInterface;

/**
 * Represents a game player.
 *
 * @package \Game\Adapter\Socket\Player
 */
class Player implements PlayerInterface
{
    /**
     * Player's IO handler.
     * @var \Game\Application\Player\IOInterface
     */
    protected $io;

    /**
     * Player name.
     *
     * @var string
     */
    protected $name;

    /**
     * Registered player.
     *
     * @var bool
     */
    protected $registered = false;

    /**
     * Player constructor.
     *
     * @param \Game\Application\Player\IOInterface $io
     */
    public function __construct(IOInterface $io)
    {
        $this->setIO($io);
    }

    /**
     * {@inheritdoc}
     */
    public function isRegistered(): bool
    {
        return $this->registered;
    }

    /**
     * {@inheritdoc}
     */
    public function setRegistered(bool $registered)
    {
        $this->registered = $registered;
    }

    /**
     * {@inheritdoc}
     */
    public function getIO(): IOInterface
    {
        return $this->io;
    }

    /**
     * {@inheritdoc}
     */
    public function setIO(IOInterface $io): void
    {
        $this->io = $io;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
