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
namespace Game\Adapter\Socket\Shape;

/**
 * Rock-Paper-Scissors-Lizard-Spock game mode handler.
 *
 * @package \Game\Adapter\Socket\Shape
 */
class RockPaperScissorsLizardSpock extends RockPaperScissors
{
    /**
     * Shape winning rules.
     *
     * @var array
     */
    protected static $winTable = [
        'rock' => ['rock' => 0, 'paper' => -1, 'scissors' => 1, 'lizard' => 1, 'spock' => -1],
        'paper' => ['rock' => 1, 'paper' => 0, 'scissors' => -1, 'lizard' => -1, 'spock' => 1],
        'scissors' => ['rock' => -1, 'paper' => 1, 'scissors' => 0, 'lizard' => 1, 'spock' => -1],
        'lizard' => ['rock' => -1, 'paper' => 1, 'scissors' => -1, 'lizard' => 0, 'spock' => 1],
        'spock' => ['rock' => 1, 'paper' => -1, 'scissors' => 1, 'lizard' => -1, 'spock' => 0],
    ];
}
