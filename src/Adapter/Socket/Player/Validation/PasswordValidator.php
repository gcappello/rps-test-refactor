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
namespace Game\Adapter\Socket\Player\Validation;

/**
 * Password validation class.
 *
 * @package \Game\Adapter\Socket\Player\Validation
 */
class PasswordValidator
{
    /**
     * Validates the given username value.
     *
     * @param string $password
     * @return mixed
     * @throws \Exception On validation error
     */
    public function __invoke($password)
    {
        if (strlen($password) <= 4) {
            throw new \Exception('Invalid password, four characters at least');
        }

        return $password;
    }
}
