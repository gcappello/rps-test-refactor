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
 * Used to pack game session rounds into a single structure.
 */
class RoundCollection
{
    /**
     * Collection of rounds.
     *
     * @var array[\Game\Application\Session\RoundInterface]
     */
    protected $rounds = [];

    /**
     * RoundCollection constructor.
     *
     * @param array[\Game\Application\Session\RoundInterface] $rounds Initial rounds
     */
    public function __construct(array $rounds = [])
    {
        foreach ($rounds as $round) {
            $this->append($round);
        }
    }

    /**
     * Gets a list of every round during certain game session.
     *
     * @param \Game\Application\Session\RoundInterface $round Round to append
     * @return $this
     */
    public function append(RoundInterface $round): RoundCollection
    {
        $this->rounds[] = $round;

        return $this;
    }

    /**
     * Whether this collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->rounds);
    }

    /**
     * Converts this collection into an array.
     *
     * @return array[\Game\Application\Session\RoundInterface]
     */
    public function toArray(): array
    {
        return $this->rounds;
    }

    /**
     * Counts the number of items within this collections.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->rounds);
    }

    /**
     * Who won this collection of rounds.
     *
     * @return string Possible values are: `player`, `server`, `tie`
     */
    public function overallWinner(): string
    {
        $playerWins = 0;
        $serverWins = 0;

        foreach ($this->rounds as $round) {
            if ($round->isPlayerWinner()) {
                $playerWins++;
            } elseif ($round->isServerWinner()) {
                $serverWins++;
            }
        }

        if ($playerWins > $serverWins) {
            return 'player';
        } elseif ($playerWins < $serverWins) {
            return 'server';
        }

        return 'tie';
    }
}
