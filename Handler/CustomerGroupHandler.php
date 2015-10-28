<?php

namespace CustomerGroup\Handler;

use CustomerGroup\CustomerGroup;
use CustomerGroup\Model\CustomerCustomerGroupQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\SecurityContext;
use Thelia\Model\Customer;

/**
 * Handle checks on customer groups.
 */
class CustomerGroupHandler
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get CustomerGroup of the current customer
     *
     * @return array|null
     */
    public function getGroup()
    {
        /** @var Request $request */
        $request = $this->container->get('request');

        $groupInfo = $request->getSession()->get(CustomerGroup::getModuleCode());

        return $groupInfo;
    }

    /**
     * Get CustomerGroup Code of the current customer
     *
     * @return string|null
     *
     * @uses getGroup()
     */
    public function getGroupCode()
    {
        $customerGroup = $this->getGroup();

        return (isset($customerGroup['code'])) ? $customerGroup['code'] : null;
    }

    /**
     * Check if the current customer is in the asked group
     *
     * @param string $groupCode Code for the group to check
     *
     * @return boolean
     *
     * @uses getGroupCode()
     */
    public function checkGroup($groupCode)
    {
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('thelia.securityContext');

        return $securityContext->hasCustomerUser() && $this->getGroupCode() === $groupCode;
    }

    /**
     * Check that a customer belongs to a group.
     *
     * @param Customer $customer
     * @param string $groupCode
     *
     * @return bool
     */
    public function checkCustomerHasGroup(Customer $customer, $groupCode)
    {
        $group = CustomerCustomerGroupQuery::create()
            ->filterByCustomer($customer)
            ->useCustomerGroupQuery()
            ->filterByCode($groupCode)
            ->endUse()
            ->findOne();

        return $group !== null;
    }
}
