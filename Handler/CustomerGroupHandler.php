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
     * Check if the current current customer is in the asked group
     *
     * @param string $groupCode Code for the group to check
     *
     * @return boolean
     */
    public function checkGroup($groupCode)
    {
        /** @var Request $request */
        $request = $this->container->get('request');
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get('thelia.securityContext');

        $groupInfo = $request->getSession()->get(CustomerGroup::getModuleCode());

        return $securityContext->hasCustomerUser() && $groupInfo !== null && $groupInfo['code'] === $groupCode;
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
