<?php

namespace CustomerGroup\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event for customer group creation.
 */
class CreateCustomerGroup extends Event
{
    /** @var string */
    protected $code;
    /** @var boolean */
    protected $is_default;
    /** @var string */
    protected $title;
    /** @var string */
    protected $description;

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param boolean $is_default
     * @return $this
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }
}
