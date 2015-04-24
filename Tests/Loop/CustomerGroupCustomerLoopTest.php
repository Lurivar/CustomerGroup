<?php

namespace CustomerGroup\Tests\Loop;

use CustomerGroup\Event\AddCustomerToCustomerGroupEvent;
use CustomerGroup\Event\CustomerGroupEvents;
use CustomerGroup\Loop\CustomerGroupCustomerLoop;
use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Propel\Runtime\Util\PropelModelPager;
use Thelia\Model\Customer;

/**
 * Tests for CustomerGroupCustomerLoop.
 */
class CustomerGroupCustomerLoopTest extends AbstractCustomerGroupTest
{
    /**
     * The loop under test.
     * @var CustomerGroupCustomerLoop
     */
    protected $loop;

    /**
     * Test arguments.
     * @var array
     */
    protected $testArguments = [];

    public function setUp()
    {
        parent::setUp();

        $this->loop = new CustomerGroupCustomerLoop($this->container);

        /** @var Customer $testCustomer */
        $testCustomer = static::$testCustomers[0];
        /** @var CustomerGroup $testGroup */
        $testGroup = static::$testCustomerGroups[0];

        $addToGroupEvent = new AddCustomerToCustomerGroupEvent();
        $addToGroupEvent
            ->setCustomerId($testCustomer->getId())
            ->setCustomerGroupId($testGroup->getId());
        $this->dispatcher->dispatch(CustomerGroupEvents::ADD_CUSTOMER_TO_CUSTOMER_GROUP, $addToGroupEvent);

        $this->testArguments = [
            "customer_group_id" => $testGroup->getId(),
            "customer_group_code" => $testGroup->getCode(),
            "customer_group_is_default" => $testGroup->getIsDefault(),
            "current" => false,
        ];
    }

    /**
     * @covers CustomerGroupCustomerLoop::initializeArgs()
     */
    public function testHasNoMandatoryArguments()
    {
        $this->loop->initializeArgs([]);
    }

    /**
     * @covers CustomerGroupCustomerLoop::initializeArgs()
     */
    public function testAcceptsAllArguments()
    {
        $this->loop->initializeArgs($this->testArguments);
    }

    /**
     * @covers CustomerGroupCustomerLoop::buildModelCriteria()
     * @covers CustomerGroupCustomerLoop::exec()
     * @covers CustomerGroupCustomerLoop::parseResults()
     */
    public function testHasExpectedOutput()
    {
        /** @var Customer $testCustomer */
        $testCustomer = static::$testCustomers[0];
        /** @var CustomerGroup $testGroup */
        $testGroup = static::$testCustomerGroups[0];

        $this->loop->initializeArgs($this->testArguments);
        $loopResult = $this->loop->exec(
            new PropelModelPager($this->loop->buildModelCriteria())
        );

        $this->assertEquals(1, $loopResult->getCount());

        $loopResult->rewind();
        $loopResultRow = $loopResult->current();
        $this->assertEquals($testCustomer->getId(), $loopResultRow->get("ID")); // from the customer loop
        $this->assertEquals($testGroup->getId(), $loopResultRow->get("CUSTOMER_GROUP_ID"));
        $this->assertEquals($testGroup->getCode(), $loopResultRow->get("CUSTOMER_GROUP_CODE"));
        $this->assertEquals($testGroup->getIsDefault(), $loopResultRow->get("CUSTOMER_GROUP_DEFAULT"));
    }
}
