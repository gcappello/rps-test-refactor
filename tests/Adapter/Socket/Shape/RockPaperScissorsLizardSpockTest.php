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

use Game\Adapter\Socket\Shape\RockPaperScissorsLizardSpock;
use PHPUnit\Framework\TestCase;

class RockPaperScissorsLizardSpockTest extends TestCase
{
    public function testCompare()
    {
        $shapes = new RockPaperScissorsLizardSpock();

        $this->assertEquals(0, $shapes->compare('rock', 'rock'));
        $this->assertEquals(-1, $shapes->compare('rock', 'paper'));
        $this->assertEquals(1, $shapes->compare('rock', 'scissors'));
        $this->assertEquals(1, $shapes->compare('rock', 'lizard'));
        $this->assertEquals(-1, $shapes->compare('rock', 'spock'));

        $this->assertEquals(1, $shapes->compare('paper', 'rock'));
        $this->assertEquals(0, $shapes->compare('paper', 'paper'));
        $this->assertEquals(-1, $shapes->compare('paper', 'scissors'));
        $this->assertEquals(-1, $shapes->compare('paper', 'lizard'));
        $this->assertEquals(1, $shapes->compare('paper', 'spock'));

        $this->assertEquals(-1, $shapes->compare('scissors', 'rock'));
        $this->assertEquals(1, $shapes->compare('scissors', 'paper'));
        $this->assertEquals(0, $shapes->compare('scissors', 'scissors'));
        $this->assertEquals(1, $shapes->compare('scissors', 'lizard'));
        $this->assertEquals(-1, $shapes->compare('scissors', 'spock'));

        $this->assertEquals(-1, $shapes->compare('lizard', 'rock'));
        $this->assertEquals(1, $shapes->compare('lizard', 'paper'));
        $this->assertEquals(-1, $shapes->compare('lizard', 'scissors'));
        $this->assertEquals(0, $shapes->compare('lizard', 'lizard'));
        $this->assertEquals(1, $shapes->compare('lizard', 'spock'));

        $this->assertEquals(1, $shapes->compare('spock', 'rock'));
        $this->assertEquals(-1, $shapes->compare('spock', 'paper'));
        $this->assertEquals(1, $shapes->compare('spock', 'scissors'));
        $this->assertEquals(-1, $shapes->compare('spock', 'lizard'));
        $this->assertEquals(0, $shapes->compare('spock', 'spock'));
    }

    /**
     * @expectedException \Game\Application\Shape\Error\InvalidShapeException
     */
    public function testCompareInvalidShape()
    {
        $shapes = new RockPaperScissorsLizardSpock();

        $shapes->compare('spock', 'rocky');
    }

    public function testGetShapes()
    {
        $shapes = new RockPaperScissorsLizardSpock();
        $expectedShapes = ['rock', 'paper', 'scissors', 'lizard', 'spock'];

        $this->assertEmpty(array_diff($expectedShapes, $shapes->getShapes()));
    }
}
