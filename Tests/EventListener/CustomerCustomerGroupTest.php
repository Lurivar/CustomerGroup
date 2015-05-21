<?php

namespace CustomerGroup\Tests\EventListener;

use CustomerGroup\CustomerGroup as CustomerGroupModule;
use CustomerGroup\Event\AddCustomerToCustomerGroupEvent;
use CustomerGroup\Event\CustomerGroupEvents;
use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CountryQuery;
use Thelia\Model\Customer;
use Thelia\Model\CustomerTitleQuery;

/**
 * Tests for the CustomerCustomerGroup event listener.
 */
class CustomerCustomerGroupTest extends AbstractCustomerGroupTest
{
    /**
     * @covers CustomerCustomerGroup::addDefaultCustomerGroupToCustomer()
     */
    public function testAddDefaultCustomerGroupToCustomerNoDefaultGroup()
    {
        // unset the default group
        /** @var CustomerGroup $defaultGroup */
        $defaultGroup = self::$testCustomerGroups[0];
        $defaultGroup
            ->setIsDefault(false)
            ->save();

        $newCustomer = new Customer();
        $newCustomer->setDispatcher($this->dispatcher);

        $newCustomer->createOrUpdate(
            CustomerTitleQuery::create()->findOneByByDefault(true)->getId(),
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            CountryQuery::create()->findOneByByDefault(true)->getId(),
            "foo",
            "foo"
        );

        // the customer should not be in any groups
        foreach (self::$TEST_CUSTOMER_GROUP_CODES as $groupCode) {
            $this->assertFalse(
                $this->customerGroupHandler->checkCustomerHasGroup($newCustomer, $groupCode)
            );
        }

        // reset the default group
        $defaultGroup
            ->setIsDefault(true)
            ->save();
    }

    /**
     * @covers CustomerCustomerGroup::addDefaultCustomerGroupToCustomer()
     */
    public function testNewCustomerIsAddedToDefaultGroup()
    {
        $newCustomer = new Customer();
        $newCustomer->setDispatcher($this->dispatcher);

        $newCustomer->createOrUpdate(
            CustomerTitleQuery::create()->findOneByByDefault(true)->getId(),
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            CountryQuery::create()->findOneByByDefault(true)->getId(),
            "foo",
            "foo"
        );

        /** @var CustomerGroup $defaultGroup */
        $defaultGroup = self::$testCustomerGroups[0];

        // assert that the new customer is in the default group, and only this one
        foreach (self::$TEST_CUSTOMER_GROUP_CODES as $groupCode) {
            $hasGroup = $this->customerGroupHandler->checkCustomerHasGroup($newCustomer, $groupCode);

            if ($groupCode == $defaultGroup->getCode()) {
                $this->assertTrue($hasGroup);
            } else {
                $this->assertFalse($hasGroup);
            }
        }
    }

    /**
     * @covers CustomerCustomerGroup::addCustomer()
     */
    public function testCanAddCustomerToGroup()
    {
        /** @var Customer $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $secondGroup */
        $secondGroup = self::$testCustomerGroups[1];

        $groupAddEvent = new AddCustomerToCustomerGroupEvent();
        $groupAddEvent
            ->setCustomerId($firstCustomer->getId())
            ->setCustomerGroupId($secondGroup->getId());

        $this->dispatcher->dispatch(CustomerGroupEvents::ADD_CUSTOMER_TO_CUSTOMER_GROUP, $groupAddEvent);

        $this->assertTrue(
            $this->customerGroupHandler->checkCustomerHasGroup($firstCustomer, $secondGroup->getCode())
        );
    }

    /**
     * @depends testCanAddCustomerToGroup
     * @covers CustomerCustomerGroup::addCustomer()
     */
    public function testCanAddCustomerToGroupTwice()
    {
        $this->testCanAddCustomerToGroup();
    }

    /**
     * @depends testCanAddCustomerToGroup
     * @covers CustomerCustomerGroup::addCustomerGroupToSession()
     */
    public function testGroupIsAddedToSessionAtLogin()
    {
        /** @var Customer $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $secondGroup */
        $secondGroup = self::$testCustomerGroups[1];

        $loginEvent = new CustomerLoginEvent($firstCustomer);
        $this->dispatcher->dispatch(TheliaEvents::CUSTOMER_LOGIN, $loginEvent);

        /** @var Request $request */
        $request = $this->container->get("request");

        $groupInfo = $request->getSession()->get(CustomerGroupModule::getModuleCode());
        $this->assertNotNull($groupInfo);
        $this->assertTrue(isset($groupInfo["id"]));
        $this->assertEquals($groupInfo["id"], $secondGroup->getId());
        $this->assertTrue(isset($groupInfo["code"]));
        $this->assertEquals($groupInfo["code"], $secondGroup->getCode());
        $this->assertTrue(isset($groupInfo["default"]));
        $this->assertEquals($groupInfo["default"], $secondGroup->getIsDefault());
    }

    /**
     * @depends testCanAddCustomerToGroup
     * @covers CustomerCustomerGroup::addCustomer()
     */
    public function testCanChangeCustomerGroup()
    {
        /** @var Customer $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $firstGroup */
        $firstGroup = self::$testCustomerGroups[0];

        $event = new AddCustomerToCustomerGroupEvent();
        $event
            ->setCustomerId($firstCustomer->getId())
            ->setCustomerGroupId($firstGroup->getId());

        $this->dispatcher->dispatch(CustomerGroupEvents::ADD_CUSTOMER_TO_CUSTOMER_GROUP, $event);

        $this->assertTrue(
            $this->customerGroupHandler->checkCustomerHasGroup($firstCustomer, $firstGroup->getCode())
        );
    }
}
