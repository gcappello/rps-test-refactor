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

use Game\Adapter\Socket\Network\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    protected $socketMock;
    protected $socketFilePath;

    function setUp()
    {
        $this->socketFilePath = realpath(__DIR__ . '/../../../../') . '/tmp/test.sock';
        @unlink($this->socketFilePath);
        $this->socketMock = socket_create(AF_UNIX, SOCK_STREAM, 0);
        socket_bind($this->socketMock, $this->socketFilePath);

        parent::setUp();
    }

    protected function tearDown()
    {
        socket_close($this->socketMock);

        parent::tearDown();
    }

    public function testGetRemoteAddress()
    {
        $connection = new Connection($this->socketMock);
        $this->assertNotEmpty($connection->getRemoteAddress());
    }
}
