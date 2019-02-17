<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

namespace Test\Application\Session;

use Game\Application\Session\RoundCollection;
use Game\Application\Session\RoundInterface;
use PHPUnit\Framework\TestCase;

class RoundCollectionTest extends TestCase
{

    public function testCount()
    {
        $collection = new RoundCollection();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @depends testCount
     */
    public function testAppend()
    {
        $round = $this->createMock(RoundInterface::class);
        $collection = new RoundCollection();
        $collection->append($round);

        $this->assertEquals(1, $collection->count());
    }

    public function testIsEmpty()
    {
        $collection = new RoundCollection();

        $this->assertTrue($collection->isEmpty());
    }

    public function testIsEmptyButNot()
    {
        $round = $this->createMock(RoundInterface::class);
        $collection = new RoundCollection([$round]);

        $this->assertFalse($collection->isEmpty());
    }

    public function testToArray()
    {
        $collection = new RoundCollection();

        $this->assertIsArray($collection->toArray());
    }

    public function testOverallWinnerTie()
    {
        $collection = new RoundCollection();

        $this->assertEquals('tie', $collection->overallWinner());
    }

    public function testOverallWinnerPlayer()
    {
        $round = $this->createMock(RoundInterface::class);
        $round->expects($this->atLeastOnce())
            ->method('isPlayerWinner')
            ->will($this->returnValue(true));

        $collection = new RoundCollection([$round]);

        $this->assertEquals('player', $collection->overallWinner());
    }

    public function testOverallWinnerServer()
    {
        $round = $this->createMock(RoundInterface::class);
        $round->expects($this->atLeastOnce())
            ->method('isServerWinner')
            ->will($this->returnValue(true));

        $collection = new RoundCollection([$round]);

        $this->assertEquals('server', $collection->overallWinner());
    }
}
