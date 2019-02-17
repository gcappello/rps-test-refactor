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

use Game\Adapter\Socket\Network\Error\ServerSocketException;
use Game\Adapter\Socket\Player\SocketIO;
use Game\Adapter\Socket\Session\Game;
use Game\Adapter\Socket\Player\Player;
use Game\Adapter\Socket\Repository\User\UserEntity;
use Game\Application\Network\ConnectionInterface;
use Game\Application\Network\Error\ClientDisconnectedException;
use Game\Application\Network\ServerInterface;
use Game\Application\Player\IOInterface;
use Game\Application\Player\PlayerInterface;
use Game\Application\Shape\ShapeHandlerInterface;
use Game\Application\Repository\User\UserRepositoryInterface;

/**
 * Simple TCP socket-based game server.
 *
 * @package \Game\Adapter\Socket\Network
 */
class Server implements ServerInterface
{

    /**
     * Number of served TCP connections.
     *
     * @var int
     */
    protected $servedConnections = 0;

    /**
     * TCP server socket.
     *
     * @var resource
     */
    protected $socket;

    /**
     * Users repository handler.
     *
     * @var \Game\Application\Repository\User\UserRepositoryInterface
     */
    protected $users;

    /**
     * Debug mode status.
     *
     * @var bool
     */
    protected $debug;

    /**
     * Instance of shape handler to use by this server.
     *
     * @var \Game\Application\Shape\ShapeHandlerInterface
     */
    protected $shapeHandler;

    /**
     * @return int
     */
    public function getServedConnections(): int
    {
        return $this->servedConnections;
    }

    /**
     * @return resource
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @return \Game\Application\Shape\ShapeHandlerInterface
     */
    public function getShapeHandler(): ShapeHandlerInterface
    {
        return $this->shapeHandler;
    }

    /**
     * Default option values for self::listen().
     *
     * @var array
     */
    protected static $defaultListenOptions = [
        'ip' => '127.0.0.1',
        'port' => 5600,
        'backlog' => 10,
        'debug' => false,
    ];

    /**
     * Server constructor.
     *
     * @param \Game\Application\Shape\ShapeHandlerInterface $shapeHandler
     * @param \Game\Application\Repository\User\UserRepositoryInterface $users
     */
    public function __construct(ShapeHandlerInterface $shapeHandler, UserRepositoryInterface $users)
    {
        $this->shapeHandler = $shapeHandler;
        $this->users = $users;
    }

    /**
     * @inheritDoc
     *
     * ## Options
     *
     * - `ip`: IPv4 biding ip, defaults to `127.0.0.1`
     * - `port`: Port number where start listening for new connections, defaults to `5600`
     * - `backlog`: Socket backlog size, defaults to `10`
     * - `debug`: Whether to print debug messages while running, defaults to `false`
     */
    public function listen(array $options = []): void
    {
        $options += self::$defaultListenOptions;
        $this->debug = $options['debug'];

        try {
            $this->socket = $this->buildServerSocket($options['ip'], intval($options['port']));
            if (@socket_listen($this->getSocket(), intval($options['backlog'])) === false) {
                throw new ServerSocketException('Unable to start listening on socket');
            }
        } catch (ServerSocketException $ex) {
            throw $ex;
        }

        $this->debug(sprintf('Server started, listening at %s:%s', $options['ip'], $options['port']));

        while (true) {
            if ($clientSocket = @socket_accept($this->getSocket())) {
                $pid = pcntl_fork();

                if ($pid) {
                    // parent
                    $this->servedConnections++;
                    $this->debug(sprintf('Client #%d arrived!', $this->getServedConnections()));
                } elseif ($pid === 0) {
                    // child
                    $this->debug('Handling new connection');
                    $this->handleConnection($this->buildConnectionFromSocket($clientSocket));
                } elseif ($pid == -1) {
                    // error
                    $this->debug('Unable to fork process to handle new incoming connection.');
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function handleConnection(ConnectionInterface $connection): void
    {
        try {
            $player = new Player($this->buildIOFromConnection($connection));
            $player->setName('Guest');
            $player = $this->doLogin($player);

            if (!$player->isRegistered()) {
                $player = $this->doRegistration($player);
            }

            $this->startNewGame($player);
        } catch (ClientDisconnectedException $ex) {
            $this->debug('Client disconnected');
        } catch (\Exception $ex) {
            $this->debug('Ops! something went wrong, see below');
            $this->debug($ex->getMessage());
        } finally {
            $connection->close();
            exit;
        }
    }

    /**
     * Starts an interactive game session.
     *
     * @param \Game\Application\Player\PlayerInterface $player
     * @throws \Game\Application\Session\Error\SessionNotReadyException
     */
    protected function startNewGame(PlayerInterface $player): void
    {
        $game = new Game($player, $this->getShapeHandler());

        $player->getIo()->write('Starting a new game!');
        $player->getIo()->write('####################');
        $player->getIO()->nl();
        $game->setMaxRounds($player->getIo()->askGameRounds());

        $gameResult = $game->play();
        $player->getIO()->write('--------------------------');
        $player->getIO()->gameResults($gameResult);
        $player->getIO()->write('--------------------------');

        $player->getIO()->nl();
        $replay = $player->getIO()->askConfirmation('Play again?');

        if ($replay) {
            $this->startNewGame($player);
        } else {
            $player->getIO()->write('Bye!');
        }
    }

    /**
     * Says hello to registered players.
     *
     * @param \Game\Application\Player\PlayerInterface $player
     * @param \DateTime $lastSeen Whe was this player seen for the last time
     */
    protected function welcome(PlayerInterface $player, \DateTime $lastSeen)
    {
        if ($player->isRegistered()) {
            $message = sprintf(
                '## Hello %s, welcome back! (last seen %s) ##',
                $player->getName(),
                $lastSeen->format('Y-m-d H:i:s e')
            );

            $player->getIO()->nl();
            $player->getIO()->write(str_repeat('#', strlen($message)));
            $player->getIO()->write($message);
            $player->getIO()->write(str_repeat('#', strlen($message)));
            $player->getIO()->nl();
        }
    }

    /**
     * Handles player login process.
     *
     * @param \Game\Application\Player\PlayerInterface $player
     * @return \Game\Application\Player\PlayerInterface
     * @throws \Exception
     */
    protected function doLogin(PlayerInterface $player): PlayerInterface
    {
        $player->setRegistered(false);
        $doLogin = $player->getIo()->askConfirmation('Want to login?', false);

        if ($doLogin) {
            do {
                $username = $player->getIo()->askPlayerUsername();
                $password = $player->getIo()->askPlayerPassword();
                $registered = $this->users->findByLogin($username, $password);

                if ($registered) {
                    $player->setRegistered(true);
                    $player->setName($registered->getName());
                    $this->welcome($player, $registered->getLastSeen());

                    $registered->setLastSeen(new \DateTime());
                    $this->users->save($registered);

                    $keepTrying = false;
                } else {
                    $keepTrying = $player->getIo()->askConfirmation('Invalid username or password, try again?', true);
                }
            } while ($keepTrying);
        }

        return $player;
    }

    /**
     * Handles player registration process.
     *
     * @param \Game\Application\Player\PlayerInterface $player
     * @return \Game\Application\Player\PlayerInterface
     * @throws \Exception
     */
    protected function doRegistration(PlayerInterface $player): PlayerInterface
    {
        $doRegistration = $player->getIo()->askConfirmation('Want to register?', false);

        if ($doRegistration) {
            do {
                $username = $player->getIO()->askPlayerUsername();
                $password = $player->getIO()->askPlayerPassword();
                $exists = $this->users->findByName($username);

                if ($exists) {
                    $player->getIO()->write('Username already in use, please try again.');
                }
            } while ($exists);

            $entity = new UserEntity();
            $entity->setName($username);
            $entity->setPassword($password);
            $entity->setLastSeen(new \DateTime());
            $this->users->save($entity);

            $player->setName($entity->getName());
            $player->setRegistered(true);
        }

        return $player;
    }

    /**
     * Creates a IO handler for the given connection.
     *
     * @param \Game\Application\Network\ConnectionInterface $connection
     *
     * @return \Game\Application\Player\IOInterface
     */
    protected function buildIOFromConnection(ConnectionInterface $connection): IOInterface
    {
        return new SocketIO($connection);
    }

    /**
     * Creates a new connection object from a client socket.
     *
     * @param resource $socket
     * @return \Game\Adapter\Socket\Network\Connection
     */
    protected function buildConnectionFromSocket($socket): ConnectionInterface
    {
        return new Connection($socket);
    }

    /**
     * Creates a new server socket ready to start listening for new connections.
     *
     * @param string $ip IPv4
     * @param int $port TCP port number
     * @return resource
     * @throws \Game\Adapter\Socket\Network\Error\ServerSocketException If something wen wrong
     * @see self::__construct()
     */
    protected function buildServerSocket(string $ip, int $port)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new ServerSocketException('Invalid `ip` option given, please provide a valid IPv4 value.');
        }

        if ($port < 1 || $port> 63000) {
            throw new ServerSocketException('Invalid `port` option given, please provide a value between 10 and 63000.');
        }

        $serverSocket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        @socket_set_option($serverSocket, SOL_SOCKET, SO_REUSEADDR, 1);
        @socket_bind($serverSocket, $ip, $port);
        $errorCode = @socket_last_error();

        if ($errorCode !== 0) {
            $errorMessage = socket_strerror($errorCode);

            throw new ServerSocketException(sprintf('Unable to build server socket: [%s] %s', $errorCode, $errorMessage));
        }

        return $serverSocket;
    }

    /**
     * Prints given message if debug is enabled.
     *
     * @param string $message Message to print
     * @param bool $pidPrefix Whether to prefix message with PID number
     */
    protected function debug(string $message, bool $pidPrefix = true): void
    {
        if ($this->isDebug()) {
            if ($pidPrefix) {
                echo sprintf("PID#%d: %s\n", getmypid(), trim($message));
            } else {
                echo "{$message}\n";
            }
        }
    }
}
