<?php

namespace CustomerGroup\EventListener;

use CustomerGroup\CustomerGroup;
use CustomerGroup\Event\AddCustomerToCustomerGroupEvent;
use CustomerGroup\Event\CustomerGroupEvents;
use CustomerGroup\Model\CustomerCustomerGroupQuery;
use CustomerGroup\Model\CustomerGroupQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Customer\CustomerEvent;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;

/**
 * Performs actions on customer groups.
 */
class CustomerCustomerGroup implements EventSubscriberInterface
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return [
            CustomerGroupEvents::ADD_CUSTOMER_TO_CUSTOMER_GROUP => ["addCustomer", 128],
            TheliaEvents::AFTER_CREATECUSTOMER => ["addDefaultCustomerGroupToCustomer", 100],
            TheliaEvents::CUSTOMER_LOGIN => ["addCustomerGroupToSession", 100],
        ];
    }

    /**
     * Add a customer to a customer group.
     * @param AddCustomerToCustomerGroupEvent $event
     */
    public function addCustomer(AddCustomerToCustomerGroupEvent $event)
    {
        (new CustomerCustomerGroupQuery())
            ->filterByCustomerId($event->getCustomerId())
            ->filterByCustomerGroupId($event->getCustomerGroupId())
            ->findOneOrCreate()
            ->save();
    }

    /**
     * Add the customer to the default customer group.
     * @todo Only if there is no customer group in the event !
     * @param CustomerEvent $event
     */
    public function addDefaultCustomerGroupToCustomer(CustomerEvent $event)
    {
        (new CustomerCustomerGroupQuery())
            ->filterByCustomerId($event->getCustomer()->getId())
            ->filterByCustomerGroupId(
                CustomerGroupQuery::create()->findOneByIsDefault(true)->getId()
            )
            ->findOneOrCreate()
            ->save();
    }

    /**
     * Add customer group information for the customer in the session.
     * Only information on the first group is added.
     * Group information is added to the session attributes with this module code as a key.
     * Structure:
     *     "id" => group id
     *     "code" => group code
     *     "default" => whether the group is the default group
     *
     * @param CustomerLoginEvent $event
     *
     * @todo Clarify if a customer can have multiple groups.
     */
    public function addCustomerGroupToSession(CustomerLoginEvent $event)
    {
        $customerGroup = CustomerGroupQuery::create()
            ->useCustomerCustomerGroupQuery()
            ->filterByCustomerId($event->getCustomer()->getId())
            ->endUse()
            ->findOne();
        if ($customerGroup === null) {
            return;
        }

        $this->request->getSession()->set(
            CustomerGroup::getModuleCode(),
            [
                "id" => $customerGroup->getId(),
                "code" => $customerGroup->getCode(),
                "default" => $customerGroup->getIsDefault(),
            ]
        );
    }
}
