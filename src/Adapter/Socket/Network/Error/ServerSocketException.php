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
namespace Game\Adapter\Socket\Network\Error;

use Game\Application\Network\Error\ListenException;

/**
 * Use when server socket fails.
 *
 * @package \Game\Adapter\Socket\Network\Error
 */
class ServerSocketException extends ListenException
{
}
