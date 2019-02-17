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
namespace Game\Application\Network;

/**
 * Any incoming and outgoing connection is represented by this interface,
 * such as a normal TCP/IP connection.
 */
interface ConnectionInterface
{

    /**
     * Returns the full remote address (URI) where this connection has been established with.
     *
     * @return string|null Remote address (URI) or null if unknown
     * @throws \Game\Application\Network\Error\ClientDisconnectedException On disconnection
     */
    public function getRemoteAddress(): ?string;

    /**
     * Waits for endpoint and reads line from wire.
     *
     * @return string
     * @throws \Game\Application\Network\Error\ClientDisconnectedException On disconnection
     */
    public function read(): ?string;

    /**
     * Writes the given content into the wire.
     *
     * @param mixed $data Data to be written
     * @return void
     * @throws \Game\Application\Network\Error\ClientDisconnectedException On disconnection
     */
    public function write($data): void;

    /**
     * Closes this connection.
     *
     * @return void
     */
    public function close(): void;
}
