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
 * Game rounds validation class.
 *
 * @package \Game\Adapter\Socket\Player\Validation
 */
class RoundsValidator
{

    /**
     * Validates the given rounds value.
     *
     * @param string $number
     * @return mixed
     * @throws \Exception On validation error
     */
    public function __invoke($number)
    {
        $number = intval($number);

        if (1 <= $number && $number <= 10) {
            return $number;
        }

        throw new \Exception('Please indicate a number between 1 and 10');
    }
}
