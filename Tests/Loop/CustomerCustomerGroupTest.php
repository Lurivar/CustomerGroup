<?php

namespace CustomerGroup\Tests\Loop;

use CustomerGroup\Event\AddCustomerToCustomerGroupEvent;
use CustomerGroup\Event\CustomerGroupEvents;
use CustomerGroup\Loop\CustomerCustomerGroup as CustomerCustomerGroupLoop;
use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Propel\Runtime\Util\PropelModelPager;
use Thelia\Model\Customer;

/**
 * Tests for the CustomerCustomerGroup loop.
 */
class CustomerCustomerGroupTest extends AbstractCustomerGroupTest
{
    /**
     * The loop under test.
     * @var CustomerCustomerGroupLoop
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

        $this->loop = new CustomerCustomerGroupLoop($this->container);

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
            "customer" => $testCustomer->getId(),
            "customer_group" => $testGroup->getId(),
        ];
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerCustomerGroup::initializeArgs()
     */
    public function testHasNoMandatoryArguments()
    {
        $this->loop->initializeArgs([]);
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerCustomerGroup::initializeArgs()
     */
    public function testAcceptsAllArguments()
    {
        $this->loop->initializeArgs($this->testArguments);
    }

    /**
     * @covers \CustomerGroup\Loop\CustomerCustomerGroup::buildModelCriteria()
     * @covers \CustomerGroup\Loop\CustomerCustomerGroup::exec()
     * @covers \CustomerGroup\Loop\CustomerCustomerGroup::parseResults()
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
        $this->assertEquals($testCustomer->getId(), $loopResultRow->get("CUSTOMER_ID"));
        $this->assertEquals($testGroup->getId(), $loopResultRow->get("CUSTOMER_GROUP_ID"));
    }
}
