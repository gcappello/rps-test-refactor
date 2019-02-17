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
namespace Test\Adapter\Socket\Player;

use Game\Adapter\Socket\Player\SocketIO;
use Game\Application\Network\ConnectionInterface;
use Game\Application\Session\GameInterface;
use Game\Application\Session\RoundCollection;
use Game\Application\Shape\ShapeHandlerInterface;
use PHPUnit\Framework\TestCase;

class SocketIOTest extends TestCase
{

    public function testAskShape()
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $game = $this->createMock(GameInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $roundCollection = $this->createMock(RoundCollection::class);

        $connection->expects($this->atLeastOnce())
            ->method('read')
            ->will($this->returnValue('rock'));
        $game->expects($this->atLeastOnce())
            ->method('getShapeHandler')
            ->will($this->returnValue($shapeHandler));
        $game->expects($this->atLeastOnce())
            ->method('getRoundCollection')
            ->will($this->returnValue($roundCollection));
        $game->expects($this->atLeastOnce())
            ->method('getMaxRounds')
            ->will($this->returnValue(1));
        $shapeHandler->expects($this->atLeastOnce())
            ->method('getShapes')
            ->will($this->returnValue(['rock', 'paper', 'scissors']));
        $roundCollection->expects($this->atLeastOnce())
            ->method('count')
            ->will($this->returnValue(1));

        $io = new SocketIO($connection);
        $playerShape = $io->askShape($game);

        $this->assertEquals('rock', $playerShape);
    }

    public function testAsk()
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue('Chris'));

        $io = new SocketIO($connection);
        $answer = $io->ask('Whats your name?');
        $this->assertEquals('Chris', $answer);
    }

    public function testAskEmptyResponses()
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue(''));

        $io = new SocketIO($connection);
        $answer = $io->ask('Whats your name?', 'DEFAULT_NAME');
        $this->assertEquals('DEFAULT_NAME', $answer);
    }

    public function testAskDefaultAnswer()
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue(''));

        $io = new SocketIO($connection);
        $answer = $io->ask('Whats your name?', 'Guest');
        $this->assertEquals('Guest', $answer);
    }

    public function testWrite()
    {
        $message = 'Dummy Content';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->once())
            ->method('write')
            ->with("{$message}\n");

        $io = new SocketIO($connection);
        $io->write($message);
    }

    public function testWriteNoNewLine()
    {
        $message = 'Dummy Content';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->once())
            ->method('write')
            ->with($message);

        $io = new SocketIO($connection);
        $io->write($message, false);
    }

    public function testAskConfirmation()
    {
        $message = 'Are you ready?';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->onConsecutiveCalls('yes', 'no', ''));

        $io = new SocketIO($connection);

        $this->assertTrue($io->askConfirmation($message));
        $this->assertFalse($io->askConfirmation($message));
        $this->assertTrue($io->askConfirmation($message));
    }

    public function testAskConfirmationMultipleInvalidResponses()
    {
        $message = 'Are you ready?';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->atMost(3))
            ->method('read')
            ->will($this->onConsecutiveCalls('invalid 1', 'invalid 2', 'yes'));

        $io = new SocketIO($connection);

        $this->assertTrue($io->askConfirmation($message));
    }

    public function testAskAndValidate()
    {
        $message = 'Give me a number < 200';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->onConsecutiveCalls('200', '50'));
        $validator = function ($answer) {
            $answer = intval($answer);

            if ($answer < 200) {
                return $answer;
            }

            throw new \Exception('Number must be < 200');
        };

        $io = new SocketIO($connection);
        $answerOne = $io->askAndValidate($message, $validator);

        $this->assertEquals(50, $answerOne);
    }

    /**
     * @expectedException \Exception
     */
    public function testAskAndValidateThrows()
    {
        $message = 'Give me a number < 200';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->onConsecutiveCalls('200', '300'));
        $validator = function ($answer) {
            $answer = intval($answer);

            if ($answer < 200) {
                return $answer;
            }

            throw new \Exception('Number must be < 200');
        };

        $io = new SocketIO($connection);
        $answerOne = $io->askAndValidate($message, $validator, 2);
    }

    public function testWriteError()
    {
        $message = 'Ops! an error occurred';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->once())
            ->method('write')
            ->with("[ERROR] {$message}\n");

        $io = new SocketIO($connection);
        $io->writeError($message);
    }

    public function testSelect()
    {
        $message = 'Indicate shape';
        $choices = ['rock', 'paper', 'scissors'];
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue('rock'));

        $io = new SocketIO($connection);
        $selection = $io->select($message, $choices, 'paper');

        $this->assertEquals('rock', $selection);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSelectInvalidOptionThrowsInOneAttempt()
    {
        $message = 'Indicate shape';
        $choices = ['rock', 'paper', 'scissors'];
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue('invalid'));

        $io = new SocketIO($connection);
        $io->select($message, $choices, 'paper', 1);
    }

    public function testSelectSecondChance()
    {
        $message = 'Indicate shape';
        $choices = ['rock', 'paper', 'scissors'];
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->onConsecutiveCalls('invalid', 'paper'));

        $io = new SocketIO($connection);
        $selection = $io->select($message, $choices, 'paper', 2);

        $this->assertEquals('paper', $selection);
    }

    public function testSelectRandomPickInfiniteAttempts()
    {
        $message = 'Indicate shape';
        $choices = ['rock', 'paper', 'scissors'];
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnCallback(function () use ($choices) {
                $key = random_int(0, count($choices) - 1);

                return $choices[$key];
            }));

        $io = new SocketIO($connection);
        $selection = $io->select($message, $choices, 'paper', null);

        $this->assertContains($selection, $choices);
    }

    public function testNl()
    {
        $multiplier = 5;
        $writeCounts = 0;
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->atLeastOnce())
            ->method('write')
            ->will($this->returnCallback(function () use (&$writeCounts) {
                $writeCounts++;
            }));

        $io = new SocketIO($connection);
        $io->nl($multiplier);

        $this->assertEquals($multiplier, $writeCounts);
    }

    public function testAskPlayerUsername()
    {
        $expectedName = 'Chris';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue($expectedName));

        $io = new SocketIO($connection);
        $username = $io->askPlayerUsername();

        $this->assertEquals($expectedName, $username);
    }

    public function testAskPlayerPassword()
    {
        $expectedPassword = '123456';
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue($expectedPassword));

        $io = new SocketIO($connection);
        $password = $io->askPlayerPassword();

        $this->assertEquals($expectedPassword, $password);
    }

    public function testAskGameRounds()
    {
        $expectedNumber = 3;
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->any())
            ->method('read')
            ->will($this->returnValue((string)$expectedNumber));

        $io = new SocketIO($connection);
        $rounds = $io->askGameRounds();

        $this->assertEquals($expectedNumber, $rounds);
    }

    public function testGameResults()
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $rounds = $this->createMock(RoundCollection::class);
        $rounds->expects($this->atLeastOnce())
            ->method('overallWinner');

        $io = new SocketIO($connection);
        $rounds = $io->gameResults($rounds);
    }
}
