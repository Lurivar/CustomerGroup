<?php

namespace CustomerGroup\Tests\EventListener;

use CustomerGroup\CustomerGroup;
use CustomerGroup\EventListener\ModuleEventListener;
use CustomerGroup\Handler\ConfigurationFileHandler;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Thelia\Core\Event\Module\ModuleToggleActivationEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\ModuleQuery;

/**
 * Tests for ModuleEventListener.
 */
class ModuleEventListenerTest extends AbstractCustomerGroupTest
{
    /**
     * Mock ConfigurationFileHandler.
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $configurationFileHandler;

    public function setUp()
    {
        parent::setUp();

        // get a mock ConfigurationFileHandler
        $this->configurationFileHandler = $this
            ->getMockBuilder(ConfigurationFileHandler::class)
            ->setMethods([
                "loadConfigurationFile"
            ])
            ->getMock();

        // register the ModuleEventListener under test
        $this->dispatcher->addSubscriber(new ModuleEventListener($this->configurationFileHandler));
    }

    /**
     * @covers ModuleEventListener::loadCustomerGroupConfigFile()
     */
    public function testModuleConfigurationIsLoadedOnActivation()
    {
        // use this module for testing
        $testModule = ModuleQuery::create()->findOneByCode(CustomerGroup::getModuleCode());
        // deactivate it
        $testModule->reload();
        $testModule->setActivate(false)->save();

        // we expect the group configuration for our module to be loaded
        $this->configurationFileHandler
            ->expects($this->once())
            ->method("loadConfigurationFile")
            ->with($this->equalTo($testModule));

        // toggle the module
        $activationEvent = new ModuleToggleActivationEvent($testModule->getId());
        $this->dispatcher->dispatch(TheliaEvents::MODULE_TOGGLE_ACTIVATION, $activationEvent);
    }

    /**
     * @covers ModuleEventListener::loadCustomerGroupConfigFile()
     */
    public function testModuleConfigurationIsNotLoadedOnDeactivation()
    {
        // use this module for testing
        $testModule = ModuleQuery::create()->findOneByCode(CustomerGroup::getModuleCode());
        // activate it
        $testModule->reload();
        $testModule->setActivate(true)->save();

        // we expect the group configuration for our module to NOT be loaded
        $this->configurationFileHandler
            ->expects($this->never())
            ->method("loadConfigurationFile")
            ->with($this->equalTo($testModule));

        // toggle the module
        $activationEvent = new ModuleToggleActivationEvent($testModule->getId());
        $this->dispatcher->dispatch(TheliaEvents::MODULE_TOGGLE_ACTIVATION, $activationEvent);
    }
}
