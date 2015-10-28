<?php

namespace CustomerGroup\Tests\Handler;

use CustomerGroup\Event\AddCustomerToCustomerGroupEvent;
use CustomerGroup\Event\CustomerGroupEvents;
use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Customer as CustomerModel;

/**
 * Tests for CustomerGroupHandler.
 */
class CustomerGroupHandlerTest extends AbstractCustomerGroupTest
{

    /**
     * @covers CustomerGroupHandler::getGroup()
     */
    public function testGetGroup()
    {
        /** @var CustomerModel $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $firstCustomerGroup */
        $firstCustomerGroup = self::$testCustomerGroups[0];
        /** @var CustomerGroup $secondCustomerGroup */
        $secondCustomerGroup = self::$testCustomerGroups[1];

        // login the customer
        $loginEvent = new CustomerLoginEvent($firstCustomer);

        $this->dispatcher->dispatch(TheliaEvents::CUSTOMER_LOGIN, $loginEvent);

        $this->assertEquals(
            $firstCustomerGroup,
            $this->customerGroupHandler->getGroup()
        );
        $this->assertNotEquals(
            $secondCustomerGroup,
            $this->customerGroupHandler->getGroup()
        );
    }

    /**
     * @covers CustomerGroupHandler::getGroupCode()
     */
    public function testGetGroupCode()
    {
        /** @var CustomerModel $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $firstCustomerGroup */
        $firstCustomerGroup = self::$testCustomerGroups[0];
        /** @var CustomerGroup $secondCustomerGroup */
        $secondCustomerGroup = self::$testCustomerGroups[1];

        // login the customer
        $loginEvent = new CustomerLoginEvent($firstCustomer);

        $this->dispatcher->dispatch(TheliaEvents::CUSTOMER_LOGIN, $loginEvent);

        $this->assertEquals(
            $firstCustomerGroup->getCode(),
            $this->customerGroupHandler->getGroup()
        );
        $this->assertNotEquals(
            $secondCustomerGroup->getCode(),
            $this->customerGroupHandler->getGroup()
        );
    }

    /**
     * @covers CustomerGroupHandler::checkCustomerHasGroup()
     */
    public function testCheckCustomerHasGroup()
    {
        /** @var CustomerModel $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $firstCustomerGroup */
        $firstCustomerGroup = self::$testCustomerGroups[0];

        // add the customer to a group
        $groupAddEvent = new AddCustomerToCustomerGroupEvent();
        $groupAddEvent
            ->setCustomerId($firstCustomer->getId())
            ->setCustomerGroupId($firstCustomerGroup->getId());

        $this->dispatcher->dispatch(CustomerGroupEvents::ADD_CUSTOMER_TO_CUSTOMER_GROUP, $groupAddEvent);

        $this->assertTrue(
            $this->customerGroupHandler->checkCustomerHasGroup($firstCustomer, $firstCustomerGroup->getCode())
        );
    }

    /**
     * @depends testCheckCustomerHasGroup
     * @covers CustomerGroupHandler::checkGroup()
     */
    public function testCheckCustomerGroupFromSession()
    {
        /** @var CustomerModel $firstCustomer */
        $firstCustomer = self::$testCustomers[0];
        /** @var CustomerGroup $firstCustomerGroup */
        $firstCustomerGroup = self::$testCustomerGroups[0];

        // login the customer
        $loginEvent = new CustomerLoginEvent($firstCustomer);

        $this->dispatcher->dispatch(TheliaEvents::CUSTOMER_LOGIN, $loginEvent);

        $this->assertTrue(
            $this->customerGroupHandler->checkGroup($firstCustomerGroup->getCode())
        );
    }

}
