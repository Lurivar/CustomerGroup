<?php

namespace CustomerGroup\Tests;

use CustomerGroup\EventListener\CustomerCustomerGroup;
use CustomerGroup\EventListener\ModuleEventListener;
use CustomerGroup\Handler\ConfigurationFileHandler;
use CustomerGroup\Handler\CustomerGroupHandler;
use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Model\CustomerGroupQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Action\Customer as CustomerAction;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\SecurityContext;
use Thelia\Core\Template\ParserInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\Country;
use Thelia\Model\CountryQuery;
use Thelia\Model\Customer;
use Thelia\Model\CustomerTitle;
use Thelia\Model\CustomerTitleQuery;
use Thelia\Model\Module;
use Thelia\Tests\ContainerAwareTestCase;

/**
 * Base class for CustomerGroup tests.
 */
abstract class AbstractCustomerGroupTest extends ContainerAwareTestCase
{
    /**
     * Number of customers to create for testing.
     * @var int
     */
    const TEST_CUSTOMERS_COUNT = 2;

    /**
     * Codes for the test groups.
     * These codes may be changed to ensure uniqueness.
     * The first one will be the default group.
     * @var array
     */
    protected static $TEST_CUSTOMER_GROUP_CODES = [
        "-customer-group-unit-test-group-a-",
        "-customer-group-unit-test-group-b-",
    ];

    /**
     * Customers to be used for tests.
     * @var array
     */
    protected static $testCustomers = [];

    /**
     * Customer groups to be used for tests.
     * @var array
     */
    protected static $testCustomerGroups = [];

    /**
     * The customer group handler.
     * @var CustomerGroupHandler
     */
    protected $customerGroupHandler;

    /**
     * Test event dispatcher.
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Absolute path of the test modules directory.
     * @var string
     */
    protected static $testModulesPath;

    protected function buildContainer(ContainerBuilder $builder)
    {
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        Propel::getConnection()->beginTransaction();

        for ($i = 0; $i < static::TEST_CUSTOMERS_COUNT; ++$i) {
            static::$testCustomers[$i] = static::makeTestCustomer();
        }
        static::makeTestGroups();
    }

    public function setUp()
    {
        parent::setUp();

        self::$testModulesPath
            = __DIR__
            . DIRECTORY_SEPARATOR . "fixtures"
            . DIRECTORY_SEPARATOR . "modules";

        $this->customerGroupHandler = new CustomerGroupHandler($this->container);

        /** @var Request $request */
        $request = $this->container->get("request");

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new CustomerCustomerGroup($request));
        $this->dispatcher->addSubscriber(new ModuleEventListener(new ConfigurationFileHandler()));
        // add the Customer action: we need it to be able to login customers
        /** @var SecurityContext $securityContext */
        $securityContext = $this->container->get("thelia.securityContext");
        $this->dispatcher->addSubscriber(
            new CustomerAction(
                $securityContext,
                new MailerFactory(
                    $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface'),
                    $this->getMock('Thelia\Core\Template\ParserInterface')
                )
            )
        );
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        Propel::getConnection()->rollBack();

        // clear the static variables for the next class instance
        static::$testCustomers = [];
        static::$testCustomerGroups = [];
    }

    /**
     * Create a test customer.
     * Optionally use a customer model as a base. This method uses the Customer::createOrUpdate method, which will
     *     overwrite some fields. Use it only for things such as adding a dispatcher.
     * @param Customer|null $customer Base customer.
     * @return Customer
     * @throws PropelException
     */
    public static function makeTestCustomer(Customer $customer = null)
    {
        if (null === $customer) {
            $customer = new Customer();
        }

        // make sure we have a customer title and country available, as they are required to create a customer
        if (null === $customerTitle = CustomerTitleQuery::create()->findOneByByDefault(true)) {
            $customerTitle = new CustomerTitle();
            $customerTitle->save();
        }

        if (null === $country = CountryQuery::create()->findOneByByDefault(true)) {
            $country = new Country();
            $country->save();
        }

        // Customer::createOrUpdate() uses the Translator, make sure it has been instantiated
        new Translator(new Container());

        $customer->createOrUpdate(
            $customerTitle->getId(),
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            $country->getId(),
            "foo",
            "foo"
        );

        return $customer;
    }

    /**
     * Create test customer groups.
     * @throws PropelException
     */
    protected static function makeTestGroups()
    {
        // unset the default group if there is one
        if (null !== $defaultGroup = CustomerGroupQuery::create()->findOneByIsDefault(true)) {
            $defaultGroup->reload();
            $defaultGroup->setIsDefault(false)->save();
        }

        // create our test groups
        $needDefaultGroup = true;
        foreach (static::$TEST_CUSTOMER_GROUP_CODES as $i => $customerGroupCode) {
            // ensure the group does not exists yet
            while (null !== CustomerGroupQuery::create()->findOneByCode($customerGroupCode)) {
                $customerGroupCode .= rand(0, 9);
            }
            static::$TEST_CUSTOMER_GROUP_CODES[$i] = $customerGroupCode;

            $customerGroup = new CustomerGroup();
            $customerGroup->setCode($customerGroupCode);
            if ($needDefaultGroup) {
                $customerGroup->setIsDefault(true);
                $needDefaultGroup = false;
            }
            $customerGroup->setLocale("en_US");
            $customerGroup->setTitle("Test customer group");
            $customerGroup->setDescription("A test customer group.");

            $customerGroup->save();

            static::$testCustomerGroups[$i] = $customerGroup;
        }
    }

    /**
     * Create a stub class for a test module.
     * @param string $moduleName Name of the module directory.
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getStubModule($moduleName)
    {
        $modulePath = static::$testModulesPath . DIRECTORY_SEPARATOR . $moduleName;
        $moduleConfigPath = $modulePath . DIRECTORY_SEPARATOR . "Config";

        $stubModule = $this
            ->getMockBuilder('Thelia\Model\Module')
            ->getMock();

        $stubModule->method("getAbsoluteBaseDir")->willReturn($modulePath);
        $stubModule->method("getAbsoluteConfigPath")->willReturn($moduleConfigPath);

        return $stubModule;
    }
}
