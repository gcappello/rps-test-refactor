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
 * Game server definition.
 */
interface ServerInterface
{

    /**
     * Waits for client connections.
     *
     * @param array $options Server specific options
     * @return void
     * @throws \Game\Application\Network\Error\ListenException On error
     */
    public function listen(array $options = []): void;

    /**
     * Handles a new connection, starts a new game session.
     *
     * @param \Game\Application\Network\ConnectionInterface $connection Client connection
     * @return void
     */
    public function handleConnection(ConnectionInterface $connection): void;
}
