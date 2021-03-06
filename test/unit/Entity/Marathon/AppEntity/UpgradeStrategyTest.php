<?php
/**
 * Created by PhpStorm.
 * User: bthapaliya
 * Date: 13/02/17
 * Time: 15:18
 */

namespace unit\Entity\Marathon\AppEntity;

use Chapi\Entity\Marathon\AppEntity\UpgradeStrategy;

class UpgradeStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testAllKeysAreCorrect()
    {
        $keys = ["minimumHealthCapacity", "maximumOverCapacity"];

        $upgradeStrategy = new UpgradeStrategy();

        foreach ($keys as $property) {
            $this->assertObjectHasAttribute($property, $upgradeStrategy);
        }
    }

    public function testUpgradeStrategySetCorrectly()
    {
        $data = [
            "minimumHealthCapacity" => 2,
            "maximumOverCapacity" => 3
        ];
        $upgradeStrategy = new UpgradeStrategy($data);

        $this->assertEquals(2, $upgradeStrategy->minimumHealthCapacity);
        $this->assertEquals(3, $upgradeStrategy->maximumOverCapacity);
    }
}
