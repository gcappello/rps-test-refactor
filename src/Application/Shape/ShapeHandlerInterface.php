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
namespace Game\Application\Shape;

use Game\Application\Session\GameInterface;

/**
 * Defines game shapes rules.
 */
interface ShapeHandlerInterface
{
    /**
     * Compare two shapes and determines which one "wins".
     *
     * ### Returned values:
     *
     * `-1`: when $shapeA loses ($shapeB wins)
     *  `0`: tie
     *  `1`: when $shapeA wins ($shapeB loses)
     *
     * @param string $shapeA Shape A
     * @param string $shapeB Shape B
     * @return int See above
     * @throws \Game\Application\Shape\Error\InvalidShapeException When an invalid shape is given
     */
    public function compare(string $shapeA, string $shapeB): int;

    /**
     * Gets a list of valid shapes options.
     *
     * @return array[string]
     */
    public function getShapes(): array;

    /**
     * Whether the given shape is a valid shape.
     *
     * @param string $shape Shape to be validated
     * @return bool True if valid, False otherwise
     */
    public function validateShape(string $shape): bool;

    /**
     * Picks a "random" shape. Useful for server picking.
     * Note that custom implementation may implement some
     * "strategy" rather than just "random" picks.
     *
     * @param \Game\Application\Session\GameInterface|null $game Game session instance to use as context
     * @return string
     */
    public function pick(GameInterface $game = null): string;
}
