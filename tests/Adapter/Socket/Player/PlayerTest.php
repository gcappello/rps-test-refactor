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

use Game\Adapter\Socket\Player\Player;
use Game\Application\Player\IOInterface;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{

    public function testGetIO()
    {
        $io = $this->createMock(IOInterface::class);
        $player = new Player($io);

        $this->assertSame($io, $player->getIO());
    }

    public function testName()
    {
        $io = $this->createMock(IOInterface::class);
        $player = new Player($io);
        $player->setName('John Locke');

        $this->assertEquals('John Locke', $player->getName());
    }

    public function testRegistered()
    {
        $io = $this->createMock(IOInterface::class);
        $player = new Player($io);
        $player->setRegistered(true);

        $this->assertTrue($player->isRegistered());
    }
}
