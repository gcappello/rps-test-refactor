<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

namespace Test\Adapter\Socket\Player\Validation;

use Game\Adapter\Socket\Player\Validation\PasswordValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase
{
    public function testInvokeValid()
    {
        $object = new PasswordValidator();

        $this->assertEquals('123456', $object('123456'));
    }

    /**
     * @expectedException \Exception
     */
    public function testInvokeThrowsWhenOutOfRange()
    {
        $object = new PasswordValidator();
        $object('1');
    }
}
