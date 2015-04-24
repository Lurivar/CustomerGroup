<?php

namespace CustomerGroup\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event to add a customer to a group.
 */
class AddCustomerToCustomerGroupEvent extends Event
{
    /** @var int */
    protected $customer_id;
    /** @var int */
    protected $customer_group_id;

    /**
     * @param int $customer_group_id
     * @return $this
     */
    public function setCustomerGroupId($customer_group_id)
    {
        $this->customer_group_id = $customer_group_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerGroupId()
    {
        return $this->customer_group_id;
    }

    /**
     * @param int $customer_id
     * @return $this
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }
}
