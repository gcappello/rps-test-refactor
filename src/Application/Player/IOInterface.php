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

use Game\Application\Session\GameInterface;
use Game\Application\Session\RoundCollection;

/**
 * Defines how server communicates with players.
 */
interface IOInterface
{

    /**
     * Writes a message to the output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param bool $newline Whether to add a newline or not
     */
    public function write(string $messages, bool $newline = true): void;

    /**
     * Writes a message to the error output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param bool $newline Whether to add a newline or not
     */
    public function writeError(string $messages, bool $newline = true): void;

    /**
     * Asks a question to the player.
     *
     * @param string $question The question to ask
     * @param string|null $default The default answer if none is given by the player
     * @throws \RuntimeException If there is no data to read in the input stream
     * @return string|null The player answer
     */
    public function ask(string $question, string $default = null): ?string;

    /**
     * Asks player for shape choice.
     *
     * @param \Game\Application\Session\GameInterface $game Game session context
     * @throws \RuntimeException If there is no data to read in the input stream
     * @return string The player shape choice
     */
    public function askShape(GameInterface $game): string;

    /**
     * Asks a confirmation to the player.
     *
     * The question will be asked until the player answers by nothing, yes, or no.
     *
     * @param string $question The question to ask
     * @param bool $default The default answer if the player enters nothing
     * @return bool true if the player has confirmed, false otherwise
     */
    public function askConfirmation(string $question, bool $default = true): bool;

    /**
     * Asks for a value and validates the response.
     *
     * The validator receives the data to validate. It must return the
     * validated data when the data is valid and throw an exception
     * otherwise.
     *
     * @param string $question The question to ask
     * @param callable $validator A PHP callback
     * @param int $attempts Max number of times to ask before giving up (default of null means infinite)
     * @param mixed $default The default answer if none is given by the player
     * @throws \Exception When any of the validators return an error
     * @return mixed
     */
    public function askAndValidate(string $question, $validator, $attempts = null, string $default = null): ?string;

    /**
     * Asks the player to select a value.
     *
     * @param string $question The question to ask
     * @param array[string] $choices List of choices to pick from
     * @param bool|string $default The default answer if the player enters nothing
     * @param null|int $attempts Max number of times to ask before giving up (null by default, which means infinite)
     * @throws \InvalidArgumentException
     * @return string The selected value
     */
    public function select(string $question, array $choices, string $default, $attempts = null): string;

    /**
     * Sends a new empty line.
     *
     * @param int $multiplier Number of new lines to send
     * @return void
     */
    public function nl(int $multiplier = 1): void;

    /**
     * Asks for player's username.
     *
     * @return string
     */
    public function askPlayerUsername(): string;

    /**
     * Asks for player's password.
     *
     * @return string
     */
    public function askPlayerPassword(): string;

    /**
     * Asks player game rounds.
     *
     * @return int
     */
    public function askGameRounds(): int;

    /**
     * Sends game results to player.
     *
     * @param \Game\Application\Session\RoundCollection $rounds Game rounds results
     */
    public function gameResults(RoundCollection $rounds): void;
}
