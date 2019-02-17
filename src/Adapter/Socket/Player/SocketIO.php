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

use Game\Adapter\Socket\Player\Validation\PasswordValidator;
use Game\Adapter\Socket\Player\Validation\RoundsValidator;
use Game\Adapter\Socket\Player\Validation\UsernameValidator;
use Game\Application\Network\ConnectionInterface;
use Game\Application\Player\IOInterface;
use Game\Application\Session\GameInterface;
use Game\Application\Session\RoundCollection;

/**
 * Allows IO communication over a TCP connection.
 *
 * @package \Game\Adapter\Socket\Player
 */
class SocketIO implements IOInterface
{
    /**
     * Socket connection.
     * @var \Game\Application\Network\ConnectionInterface
     */
    protected $connection;

    /**
     * SocketIO constructor.
     *
     * @param \Game\Application\Network\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function write(string $messages, bool $newline = true): void
    {
        $messages .= $newline ? "\n" : '';
        $this->connection->write($messages);
    }

    /**
     * @inheritDoc
     */
    public function writeError(string $messages, bool $newline = true): void
    {
        $this->write("[ERROR] {$messages}", $newline);
    }

    /**
     * @inheritDoc
     */
    public function ask(string $question, string $default = null): ?string
    {
        $question = trim($question);
        if ($default) {
            $question = "{$question} [default: {$default}]";
        }

        $this->write($question);
        $answer = trim((string)$this->connection->read());

        if ($answer === '') {
            return $default;
        }

        return $answer;
    }

    /**
     * @inheritDoc
     */
    public function askShape(GameInterface $game): string
    {
        return $this->select(
            sprintf('- Round %d/%d, enter your shape:', $game->getRoundCollection()->count(), $game->getMaxRounds()),
            $game->getShapeHandler()->getShapes(),
            $game->getShapeHandler()->pick()
        );
    }

    /**
     * @inheritDoc
     */
    public function askConfirmation(string $question, bool $default = true): bool
    {
        $question = trim($question);
        $tail = $default ? ' [YES/no]' : ' [yes/NO]';
        $this->write($question . $tail);

        $answer = strtolower(trim($this->connection->read()));
        if (empty($answer)) {
            return $default;
        }

        if (in_array($answer, ['yes', 'y'])) {
            return true;
        } elseif (in_array($answer, ['no', 'n'])) {
            return false;
        }

        // invalid answer, repeat question
        return $this->askConfirmation($question, $default);
    }

    /**
     * @inheritDoc
     */
    public function askAndValidate(string $question, $validator, $attempts = null, string $default = null): ?string
    {
        $answer = $this->ask($question, $default);

        try {
            return $validator($answer);
        } catch (\Exception $ex) {
            $this->writeError($ex->getMessage());
            $attempts = is_int($attempts) ? $attempts - 1 : null;

            if ($attempts === null || $attempts > 0) {
                return $this->askAndValidate($question, $validator, $attempts, $default);
            }
        }

        throw new \Exception('Validation failed');
    }

    /**
     * @inheritDoc
     */
    public function select(string $question, array $choices, string $default, $attempts = null): string
    {
        $question = trim($question);
        $tail = ' (' . implode(',', $choices) . ')';
        $answer = $this->ask($question . $tail, $default);

        if (!in_array($answer, $choices)) {
            $attempts = is_int($attempts) ? $attempts - 1 : null;
            if ($attempts === null || $attempts > 0) {
                return $this->select($question, $choices, $default, $attempts);
            }

            throw new \InvalidArgumentException(sprintf('Invalid choice `%s`', $answer));
        }

        return $answer;
    }

    /**
     * @inheritDoc
     */
    public function nl(int $multiplier = 1): void
    {
        while ($multiplier > 0) {
            $multiplier--;
            $this->write('');
        }
    }

    /**
     * @inheritDoc
     */
    public function askPlayerUsername(): string
    {
        $username = 'Guest';

        try {
            $username = $this->askAndValidate(
                'What is your username?',
                new UsernameValidator(),
                null
            );
        } catch (\Exception $ex) {
            // catch: default username Guest
        }

        return $username;
    }

    /**
     * @inheritDoc
     */
    public function askPlayerPassword(): string
    {
        $password = '';

        try {
            $password = $this->askAndValidate(
                'What is your password?',
                new PasswordValidator(),
                null
            );
        } catch (\Exception $ex) {
            // catch: default username Guest
        }

        return $password;
    }

    /**
     * @inheritDoc
     */
    public function askGameRounds(): int
    {
        $rounds = 1;

        try {
            $rounds = $this->askAndValidate(
                'Please indicate the number of rounds to play [1-10]:',
                new RoundsValidator(),
                null,
                '3'
            );
        } catch (\Exception $ex) {
            // catch: default to 3 round
        }

        return intval($rounds);
    }

    /**
     * @inheritDoc
     */
    public function gameResults(RoundCollection $rounds): void
    {
        $this->write('# Game results');

        foreach ($rounds->toArray() as $index => $round) {
            $this->write(sprintf('- Round %d: %s vs %s (%s)',
                $index + 1,
                $round->getPlayerShape(),
                $round->getServerShape(),
                $round->resultForPlayer()
            ));
        }

        $this->nl();
        $this->write('## Overall winner');
        $this->write(str_replace('player', 'YOU!', $rounds->overallWinner()));
    }
}
