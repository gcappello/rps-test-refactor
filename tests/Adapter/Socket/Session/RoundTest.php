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

use Game\Adapter\Socket\Session\Round;
use Game\Application\Shape\ShapeHandlerInterface;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    public function testServerShape()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $round = new Round($shapeHandler);

        $round->setServerShape('rock');
        $this->assertEquals('rock', $round->getServerShape());
    }

    public function testPlayerShape()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $round = new Round($shapeHandler);

        $round->setPlayerShape('rock');
        $this->assertEquals('rock', $round->getPlayerShape());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testIsTie()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('rock'), $this->equalTo('rock'))
            ->will($this->returnValue(0));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('rock');
        $round->setServerShape('rock');

        $this->assertTrue($round->isTie());
        $this->assertFalse($round->isPlayerWinner());
        $this->assertFalse($round->isServerWinner());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testIsPlayerWinner()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('rock'), $this->equalTo('scissors'))
            ->will($this->returnValue(1));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('rock');
        $round->setServerShape('scissors');

        $this->assertFalse($round->isTie());
        $this->assertTrue($round->isPlayerWinner());
        $this->assertFalse($round->isServerWinner());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testIsServerWinner()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('rock'), $this->equalTo('paper'))
            ->will($this->returnValue(-1));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('rock');
        $round->setServerShape('paper');

        $this->assertFalse($round->isTie());
        $this->assertFalse($round->isPlayerWinner());
        $this->assertTrue($round->isServerWinner());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testResultForPlayerWin()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('paper'), $this->equalTo('rock'))
            ->will($this->returnValue(1));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('paper');
        $round->setServerShape('rock');

        $this->assertEquals('win', $round->resultForPlayer());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testResultForPlayerLose()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('rock'), $this->equalTo('paper'))
            ->will($this->returnValue(-1));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('rock');
        $round->setServerShape('paper');

        $this->assertEquals('lose', $round->resultForPlayer());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testResultForPlayerTie()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('paper'), $this->equalTo('paper'))
            ->will($this->returnValue(0));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('paper');
        $round->setServerShape('paper');

        $this->assertEquals('tie', $round->resultForPlayer());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testResultForServerWin()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('rock'), $this->equalTo('paper'))
            ->will($this->returnValue(-1));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('rock');
        $round->setServerShape('paper');

        $this->assertEquals('win', $round->resultForServer());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testResultForServerLose()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('paper'), $this->equalTo('rock'))
            ->will($this->returnValue(1));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('paper');
        $round->setServerShape('rock');

        $this->assertEquals('lose', $round->resultForServer());
    }

    /**
     * @depends testServerShape
     * @depends testPlayerShape
     */
    public function testResultForServerTie()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $shapeHandler->expects($this->any())
            ->method('compare')
            ->with($this->equalTo('paper'), $this->equalTo('paper'))
            ->will($this->returnValue(0));

        $round = new Round($shapeHandler);
        $round->setPlayerShape('paper');
        $round->setServerShape('paper');

        $this->assertEquals('tie', $round->resultForServer());
    }
}
