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
namespace Test\Adapter\Socket\Repository\User;

use Game\Adapter\Socket\Repository\User\UserEntity;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{

    public function testName()
    {
        $user = new UserEntity();
        $user->setName('Chris');

        $this->assertEquals('Chris', $user->getName());
    }

    public function testPassword()
    {
        $user = new UserEntity();
        $user->setPassword('123456');

        $this->assertEquals('123456', $user->getPassword());
    }

    public function testLastSeen()
    {
        $user = new UserEntity();
        $at = new \DateTime();
        $user->setLastSeen($at);

        $this->assertSame($at, $user->getLastSeen());
    }
}
