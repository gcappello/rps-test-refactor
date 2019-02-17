<?php
require __DIR__ . '/vendor/autoload.php';

use Game\Adapter\Socket\Network\Server;
use Game\Adapter\Socket\Shape\RockPaperScissors;
use Game\Adapter\Socket\Shape\RockPaperScissorsLizardSpock;
use Game\Adapter\Socket\Repository\User\UsersBucket;

if (isset($argv[1]) && $argv[1] == 'rpsls') {
    $shapeHandler = new RockPaperScissorsLizardSpock();
} else {
    $shapeHandler = new RockPaperScissors();
}

try {
    $users = new UsersBucket(__DIR__ . '/tmp/users.php');
    $server = new Server($shapeHandler, $users);
    $server->listen([
        'ip' => '0.0.0.0',
        'port' => 6000,
        'debug' => true,
    ]);
} catch (\Exception $ex) {
    die($ex->getMessage());
}
