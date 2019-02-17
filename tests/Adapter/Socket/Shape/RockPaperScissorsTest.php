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
namespace Test\Adapter\Socket\Shape;

use Game\Adapter\Socket\Shape\RockPaperScissors;
use PHPUnit\Framework\TestCase;

class RockPaperScissorsTest extends TestCase
{
    public function testCompare()
    {
        $shapes = new RockPaperScissors();

        $this->assertEquals(0, $shapes->compare('rock', 'rock'));
        $this->assertEquals(-1, $shapes->compare('rock', 'paper'));
        $this->assertEquals(1, $shapes->compare('rock', 'scissors'));

        $this->assertEquals(1, $shapes->compare('paper', 'rock'));
        $this->assertEquals(0, $shapes->compare('paper', 'paper'));
        $this->assertEquals(-1, $shapes->compare('paper', 'scissors'));

        $this->assertEquals(-1, $shapes->compare('scissors', 'rock'));
        $this->assertEquals(1, $shapes->compare('scissors', 'paper'));
        $this->assertEquals(0, $shapes->compare('scissors', 'scissors'));
    }

    /**
     * @expectedException \Game\Application\Shape\Error\InvalidShapeException
     */
    public function testCompareInvalidShape()
    {
        $shapes = new RockPaperScissors();

        $shapes->compare('spock', 'rock');
    }

    public function testGetShapes()
    {
        $shapes = new RockPaperScissors();
        $expectedShapes = ['rock', 'paper', 'scissors'];

        $this->assertEmpty(array_diff($expectedShapes, $shapes->getShapes()));
    }
}
