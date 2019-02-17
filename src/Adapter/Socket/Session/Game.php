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

use Game\Application\Session\Error\SessionNotReadyException;
use Game\Application\Session\GameInterface;
use Game\Application\Player\PlayerInterface;
use Game\Application\Session\RoundCollection;
use Game\Application\Shape\ShapeHandlerInterface;

/**
 * Game session handler.
 *
 * Ask player for input on every round and solves the match.
 *
 * @package \Game\Adapter\Socket\Session
 */
class Game implements GameInterface
{
    /**
     * Player this game is attached to.
     * @var \Game\Application\Player\PlayerInterface
     */
    protected $player;

    /**
     * Rounds collection..
     * @var \Game\Application\Session\RoundCollection
     */
    protected $rounds;

    /**
     * Number of rounds for this game session.
     *
     * @var int
     */
    protected $maxRounds;

    /**
     * Shape handler for this session.
     * @var \Game\Application\Shape\ShapeHandlerInterface
     */
    protected $shapes;

    /**
     * Game constructor.
     *
     * @param \Game\Application\Player\PlayerInterface $player
     * @param \Game\Application\Shape\ShapeHandlerInterface $shapeHandler
     * @param int $maxRounds Number of rounds
     * @param \Game\Application\Session\RoundCollection $rounds Rounds collection to use, or null to create a new one
     */
    public function __construct(
        PlayerInterface $player,
        ShapeHandlerInterface $shapeHandler,
        int $maxRounds = 3,
        RoundCollection $rounds = null
    )
    {
        $this->setPlayer($player);
        $this->setShapeHandler($shapeHandler);
        $this->setMaxRounds($maxRounds);

        $rounds = $rounds ?: new RoundCollection();
        $this->setRoundCollection($rounds);
    }

    /**
     * @inheritDoc
     */
    public function getPlayer(): ?PlayerInterface
    {
        return $this->player;
    }

    /**
     * @inheritDoc
     */
    public function setPlayer(PlayerInterface $player): void
    {
        $this->player = $player;
    }

    /**
     * @inheritDoc
     */
    public function getMaxRounds(): ?int
    {
        return $this->maxRounds;
    }

    /**
     * @inheritDoc
     */
    public function setMaxRounds(int $rounds): void
    {
        $this->maxRounds = $rounds;
    }

    /**
     * @inheritDoc
     */
    public function getRoundCollection(): ?RoundCollection
    {
        return $this->rounds;
    }

    /**
     * @inheritDoc
     */
    public function setRoundCollection(RoundCollection $rounds): void
    {
        $this->rounds = $rounds;
    }

    /**
     * @inheritDoc
     */
    public function getShapeHandler(): ?ShapeHandlerInterface
    {
        return $this->shapes;
    }

    /**
     * @inheritDoc
     */
    public function setShapeHandler(ShapeHandlerInterface $shapes): void
    {
        $this->shapes = $shapes;
    }

    /**
     * @inheritDoc
     */
    public function isReady(): bool
    {
        return
            $this->getPlayer() instanceof PlayerInterface &&
            $this->getShapeHandler() instanceof ShapeHandlerInterface &&
            $this->getRoundCollection() instanceof RoundCollection &&
            $this->getMaxRounds() > 0;

    }

    /**
     * @inheritDoc
     */
    public function play(array $options = []): RoundCollection
    {
        if (!$this->isReady()) {
            throw new SessionNotReadyException('Session is not ready to start!');
        }

        for ($i = 1; $i <= $this->getMaxRounds(); $i++) {
            $round = new Round($this->getShapeHandler());
            $this->getRoundCollection()->append($round);

            $this->getPlayer()->getIO()->write('-----------------------------------');
            $serverShape = $this->getShapeHandler()->pick($this);
            $playerShape = $this->getPlayer()->getIO()->askShape($this);

            $round->setPlayerShape($playerShape);
            $round->setServerShape($serverShape);

            $this->getPlayer()
                ->getIO()
                ->write(sprintf('(you chosen `%s`, server chose `%s`)', $playerShape, $serverShape));
        }

        return $this->getRoundCollection();
    }
}
