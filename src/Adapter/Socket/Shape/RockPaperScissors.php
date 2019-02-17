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

use Game\Application\Session\GameInterface;
use Game\Application\Shape\Error\InvalidShapeException;
use Game\Application\Shape\ShapeHandlerInterface;

/**
 * Rock-Paper-Scissors game mode handler.
 *
 * @package \Game\Adapter\Socket\Shape
 */
class RockPaperScissors implements ShapeHandlerInterface
{

    /**
     * Shape winning rules.
     *
     * @var array
     */
    protected static $winTable = [
        'rock' => ['rock' => 0, 'paper' => -1, 'scissors' => 1],
        'paper' => ['rock' => 1, 'paper' => 0, 'scissors' => -1],
        'scissors' => ['rock' => -1, 'paper' => 1, 'scissors' => 0],
    ];

    /**
     * {@inheritdoc}
     */
    public function compare(string $shapeA, string $shapeB): int
    {
        if (!$this->validateShape($shapeA)) {
            throw new InvalidShapeException(sprintf('Given shape `%s` is not a valid shape.', $shapeA));
        }

        if (!$this->validateShape($shapeB)) {
            throw new InvalidShapeException(sprintf('Given shape `%s` is not a valid shape.', $shapeB));
        }

        return static::$winTable[$shapeA][$shapeB];
    }

    /**
     * {@inheritdoc}
     */
    public function getShapes(): array
    {
        return array_keys(static::$winTable);
    }

    /**
     * {@inheritdoc}
     */
    public function validateShape(string $shape): bool
    {
        $validShapes = $this->getShapes();

        return in_array($shape, $validShapes);
    }

    /**
     * {@inheritdoc}
     */
    public function pick(GameInterface $game = null): string
    {
        $shapes = $this->getShapes();
        $key = random_int(0, count($shapes) - 1);

        return $shapes[$key];
    }
}
