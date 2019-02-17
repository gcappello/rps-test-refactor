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
namespace Test\Adapter\Socket\Network;

use Game\Adapter\Socket\Network\Server;
use Game\Application\Network\ConnectionInterface;
use Game\Application\Player\PlayerInterface;
use Game\Application\Shape\ShapeHandlerInterface;
use Game\Application\Repository\User\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testListenMissingOption()
    {
        $shapeHandler = $this->createMock(ShapeHandlerInterface::class);
        $users = $this->createMock(UserRepositoryInterface::class);
        $server = new Server($shapeHandler, $users);
        $server->listen(['port' => -1]);
    }
}
