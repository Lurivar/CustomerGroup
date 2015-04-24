<?php

namespace CustomerGroup\Tests\Handler;

use CustomerGroup\Handler\ConfigurationFileHandler;
use CustomerGroup\Model\CustomerGroupQuery;
use CustomerGroup\Tests\AbstractCustomerGroupTest;
use Thelia\Model\Module;

/**
 * Tests for the ConfigurationFileHandler.
 */
class ConfigurationFileHandlerTest extends AbstractCustomerGroupTest
{
    /**
     * The ConfigurationFileHandler under test.
     * @var ConfigurationFileHandler
     */
    protected $configurationFileHandler;

    public function setUp()
    {
        parent::setUp();

        $this->configurationFileHandler = new ConfigurationFileHandler();
    }

    /**
     * @covers ConfigurationFileHandler::loadConfigurationFile()
     */
    public function testLoadModuleWithValidConfigFile()
    {
        $testModule = $this->getStubModule("ModuleValidConfigFile");

        $initialCustomerGroups = CustomerGroupQuery::create()->find();

        $this->configurationFileHandler->loadConfigurationFile($testModule);

        $finalCustomerGroups = CustomerGroupQuery::create()->find();

        // assert that the initial groups are still there
        foreach ($initialCustomerGroups as $customerGroup) {
            $this->assertTrue($finalCustomerGroups->contains($customerGroup));
        }

        // assert that the new groups were created

        $firstGroup = CustomerGroupQuery::create()->findOneByCode("-customer-group-unit-test-first-group-");
        $this->assertNotNull($firstGroup);
        $firstGroup->setLocale("en_US");
        $this->assertEquals("First", $firstGroup->getTitle());
        $this->assertEquals("Customer Group unit test first group", $firstGroup->getDescription());

        $secondGroup = CustomerGroupQuery::create()->findOneByCode("-customer-group-unit-test-second-group-");
        $this->assertNotNull($secondGroup);
        $secondGroup->setLocale("en_US");
        $this->assertEquals("Second", $secondGroup->getTitle());
        $this->assertEquals("Customer Group unit test second group", $secondGroup->getDescription());
    }

    /**
     * @covers ConfigurationFileHandler::loadConfigurationFile()
     * @expectedException \InvalidArgumentException
     */
    public function testLoadModuleWithInvalidConfigFile()
    {
        $testModule = $this->getStubModule("ModuleInvalidConfigFile");

        $this->configurationFileHandler->loadConfigurationFile($testModule);
    }

    /**
     * @covers ConfigurationFileHandler::loadConfigurationFile()
     */
    public function testLoadModuleWithNoConfigFile()
    {
        $testModule = $this->getStubModule("ModuleNoConfigFile");

        $initialCustomerGroups = CustomerGroupQuery::create()->find();

        $this->configurationFileHandler->loadConfigurationFile($testModule);

        $finalCustomerGroups = CustomerGroupQuery::create()->find();
        $this->assertEquals($initialCustomerGroups, $finalCustomerGroups);
    }

    /**
     * @covers ConfigurationFileHandler::loadConfigurationFile()
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadModuleWithMultipleConfigFiles()
    {
        $testModule = $this->getStubModule("ModuleMultipleConfigFiles");

        $this->configurationFileHandler->loadConfigurationFile($testModule);
    }
}
