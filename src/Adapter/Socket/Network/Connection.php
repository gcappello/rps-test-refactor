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
namespace Game\Adapter\Socket\Network;

use Game\Application\Network\ConnectionInterface;
use Game\Application\Network\Error\ClientDisconnectedException;

/**
 * Represents a TCP connection between server and client.
 *
 * @package \Game\Adapter\Socket\Network
 */
class Connection implements ConnectionInterface
{
    /**
     * TCP socket.
     *
     * @var resource
     */
    protected $socket;

    /**
     * Connection constructor.
     *
     * @param resource $socket
     */
    public function __construct($socket)
    {
        $this->socket = $socket;
    }

    /**
     * @inheritDoc
     */
    public function getRemoteAddress(): ?string
    {
        $address = '';
        $port = '';
        $result = @socket_getsockname($this->socket, $address, $port);

        if ($result === false) {
            throw new ClientDisconnectedException('Client disconnected');
        }

        return "{$address}:{$port}";
    }

    /**
     * @inheritDoc
     */
    public function read() : ?string
    {
        $buffer = @socket_read($this->socket, 1024);

        if ($buffer === false) {
            throw new ClientDisconnectedException('Client disconnected');
        }

        return trim($buffer);
    }

    /**
     * @inheritDoc
     */
    public function write($data) : void
    {
        $result = @socket_write($this->socket, $data, strlen($data));

        if ($result === false) {
            throw new ClientDisconnectedException('Client disconnected');
        }
    }

    /**
     * @inheritDoc
     */
    public function close() : void
    {
        @socket_shutdown($this->socket);
        @socket_close($this->socket);
    }
}
