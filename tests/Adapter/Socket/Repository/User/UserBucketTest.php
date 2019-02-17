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

use Game\Adapter\Socket\Repository\User\UsersBucket;
use Game\Adapter\Socket\Repository\User\UserEntity;
use PHPUnit\Framework\TestCase;

class UserBucketTest extends TestCase
{
    protected $storagePath;

    function setUp()
    {
        $this->storagePath = realpath(__DIR__ . '/../../../../../') . '/tmp/users.tests.php';

        parent::setUp();
    }

    protected function tearDown()
    {
        @unlink($this->storagePath);

        parent::tearDown();
    }

    public function testSave()
    {
        $users = new UsersBucket($this->storagePath);
        $entity = new UserEntity();
        $entity->setName('Chris');
        $entity->setPassword('123456');
        $entity->setLastSeen(new \DateTime());

        $this->assertTrue($users->save($entity));
    }

    public function testFindByNameNull()
    {
        $users = new UsersBucket($this->storagePath);
        $found = $users->findByName(md5(rand(1, 9999)));

        $this->assertNull($found);
    }

    /**
     * @depends testSave
     */
    public function testFindByNameMatch()
    {
        $users = new UsersBucket($this->storagePath);
        $entity = new UserEntity();
        $entity->setName('Chris');
        $entity->setPassword('123456');
        $entity->setLastSeen(new \DateTime());
        $users->save($entity);

        $this->assertNotNull($users->findByName('Chris'));
    }

    public function testFindByLoginNull()
    {
        $users = new UsersBucket($this->storagePath);
        $found = $users->findByLogin(md5(rand(1, 9999)), md5(rand(1, 9999)));

        $this->assertNull($found);
    }

    /**
     * @depends testSave
     */
    public function testFindByLoginMatch()
    {
        $users = new UsersBucket($this->storagePath);
        $entity = new UserEntity();
        $entity->setName('Chris');
        $entity->setPassword('123456');
        $entity->setLastSeen(new \DateTime());
        $users->save($entity);

        $this->assertNotNull($users->findByLogin('Chris', '123456'));
    }
}
