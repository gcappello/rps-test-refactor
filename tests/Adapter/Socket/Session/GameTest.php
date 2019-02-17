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
namespace Test\Adapter\Socket\Session;

use Game\Adapter\Socket\Session\Game;
use Game\Application\Player\PlayerInterface;
use Game\Application\Shape\ShapeHandlerInterface;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{

    public function testConstruction()
    {
        $player = $this->createMock(PlayerInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $game = new Game($player, $shapeHandler);

        $this->assertSame($player, $game->getPlayer());
        $this->assertSame($shapeHandler, $game->getShapeHandler());
    }

    public function testMaxRounds()
    {
        $player = $this->createMock(PlayerInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $game = new Game($player, $shapeHandler);
        $rounds = 10;
        $game->setMaxRounds($rounds);

        $this->assertEquals($rounds, $game->getMaxRounds());
    }

    public function testShapeHandler()
    {
        $player = $this->createMock(PlayerInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler2 = $this->createMock(ShapeHandlerInterface::class);
        $game = new Game($player, $shapeHandler);
        $game->setShapeHandler($shapeHandler2);

        $this->assertSame($shapeHandler2, $game->getShapeHandler());
    }

    public function testReady()
    {
        $player = $this->createMock(PlayerInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $game = new Game($player, $shapeHandler, 0);

        $this->assertFalse($game->isReady());
    }

    /**
     * @expectedException  \Game\Application\Session\Error\SessionNotReadyException
     */
    public function testPlayNotReady()
    {
        $player = $this->createMock(PlayerInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $game = new Game($player, $shapeHandler, 0);
        $game->play();
    }

    public function testPlayTwoRoundsSimulation()
    {
        $player = $this->createMock(PlayerInterface::class);
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $game = new Game($player, $shapeHandler, 2);

        $shapeHandler->expects($this->atLeastOnce())
            ->method('compare')
            ->will($this->onConsecutiveCalls(0, -1));

        $rounds = $game->play()->toArray();

        $this->assertCount(2, $rounds);
        $this->assertTrue($rounds[0]->isTie());
        $this->assertTrue($rounds[1]->isServerWinner());
    }
}
