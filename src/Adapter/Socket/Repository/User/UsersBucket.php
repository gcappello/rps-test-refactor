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
namespace Game\Adapter\Socket\Repository\User;

use Game\Application\Repository\User\UserInterface;
use Game\Application\Repository\User\UserRepositoryInterface;

/**
 * Simplistic (and extremely inefficient, non-ACID!) JSON array storage solution.
 *
 * WARNING: For demonstration proposes only!
 *
 * @package \Game\Adapter\Socket\Repository\User
 */
class UsersBucket implements UserRepositoryInterface
{
    protected $storagePath;

    /**
     * UsersBucket constructor.
     *
     * @param string $storagePath
     */
    public function __construct(string $storagePath)
    {
        $this->storagePath = $storagePath;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name): ?UserInterface
    {
        $users = $this->loadFile();
        $found = null;

        foreach ($users as $index => $object) {
            if ($object->getName() == $name) {
                $found = new UserEntity();
                $found->setName($name);
                $found->setPassword($object->getPassword());
                $found->setLastSeen($object->getLastSeen());

                break;
            }
        }

        return $found;
    }

    /**
     * @inheritDoc
     */
    public function findByLogin(string $name, string $password): ?UserInterface
    {
        $users = $this->loadFile();
        $found = null;

        foreach ($users as $index => $object) {
            if ($object->getName() == $name && $object->getPassword() == $password) {
                $found = new UserEntity();
                $found->setName($name);
                $found->setPassword($password);
                $found->setLastSeen($object->getLastSeen());

                break;
            }
        }

        return $found;
    }

    /**
     * @inheritDoc
     */
    public function save(UserInterface $user): bool
    {
        $users = $this->loadFile();
        $users[$user->getName()] = $user;
        $this->putFile($users);

        return true;
    }

    protected function loadFile(): array
    {
        if (!file_exists($this->storagePath)) {
            $this->putFile([]);
        }

        $content = require $this->storagePath;

        return (array)$content;
    }

    protected function putFile(array $content): void
    {
        if (!file_put_contents($this->storagePath, '<?php return ' . var_export($content, true) . ';')) {
            throw new \Exception('Write error');
        }
    }
}
