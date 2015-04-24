<?php

namespace CustomerGroup\Tests\Loop;

use CustomerGroup\Loop\CustomerGroup as CustomerGroupLoop;
use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Propel\Runtime\Util\PropelModelPager;

/**
 * Tests for the CustomerGroup loop.
 */
class CustomerGroupTest extends AbstractCustomerGroupTest
{
    /**
     * Expected possible values for the order loop argument.
     * @var array
     */
    protected static $VALID_ORDER = [
        "id",
        "id-reverse",
        "code",
        "code-reverse",
        "title",
        "title-reverse",
        "is_default",
        "is_default-reverse",
        "position",
        "position-reverse",
    ];

    /**
     * Test arguments.
     * @var array
     */
    protected $testArguments = [];

    /**
     * The customer group loop under test.
     * @var CustomerGroupLoop
     */
    protected $loop;

    public function setUp()
    {
        parent::setUp();

        $this->loop = new CustomerGroupLoop($this->container);

        /** @var CustomerGroup $testGroup */
        $testGroup = self::$testCustomerGroups[0];
        $this->testArguments = [
            "id" => $testGroup->getId(),
            "is_default" => $testGroup->getIsDefault(),
            "code" => $testGroup->getCode(),
            "order" => "position",
            "lang" => "en_US",
        ];
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerGroup::initializeArgs()
     */
    public function testHasNoMandatoryArguments()
    {
        $this->loop->initializeArgs([]);
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerGroup::initializeArgs()
     */
    public function testAcceptsAllOrderArguments()
    {
        foreach (static::$VALID_ORDER as $order) {
            $this->loop->initializeArgs(["order" => $order]);
        }
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerGroup::initializeArgs()
     */
    public function testAcceptsAllArguments()
    {
        $this->loop->initializeArgs($this->testArguments);
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerGroup::buildModelCriteria()
     * @covers \CustomerGroup\Loop\CustomerGroup::exec()
     * @covers \CustomerGroup\Loop\CustomerGroup::parseResults()
     */
    public function testHasExpectedOutput()
    {
        /** @var CustomerGroup $testGroup */
        $testGroup = self::$testCustomerGroups[0];

        $this->loop->initializeArgs($this->testArguments);

        $loopResult = $this->loop->exec(
            new PropelModelPager($this->loop->buildModelCriteria())
        );

        $this->assertEquals(1, $loopResult->getCount());

        $loopResult->rewind();
        $loopResultRow = $loopResult->current();
        $this->assertEquals($testGroup->getId(), $loopResultRow->get("CUSTOMER_GROUP_ID"));
        $this->assertEquals($testGroup->getCode(), $loopResultRow->get("CODE"));
        $this->assertEquals($testGroup->getTitle(), $loopResultRow->get("TITLE"));
        $this->assertEquals($testGroup->getDescription(), $loopResultRow->get("DESCRIPTION"));
        $this->assertEquals($testGroup->getIsDefault(), $loopResultRow->get("IS_DEFAULT"));
        $this->assertEquals($testGroup->getPosition(), $loopResultRow->get("POSITION"));
        $this->assertEquals($testGroup->getLocale(), $loopResultRow->get("LOCALE"));
    }
}
