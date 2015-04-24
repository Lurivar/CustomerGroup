<?php

namespace CustomerGroup;

use CustomerGroup\Handler\ConfigurationFileHandler;
use CustomerGroup\Model\CustomerGroupQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Install\Database;
use Thelia\Model\Module;
use Thelia\Model\ModuleQuery;
use Thelia\Module\BaseModule;

/**
 * Class CustomerGroup
 */
class CustomerGroup extends BaseModule
{
    /**
     * @var string Translation domain name
     */
    const MESSAGE_DOMAIN = 'customergroup';

    public function preActivation(ConnectionInterface $con = null)
    {
        $insert = false;
        $activate = true;

        try {
            CustomerGroupQuery::create()->findOne();
        } catch (PropelException $exception) {
            $insert = true;
        }

        if ($insert) {
            try {
                $database = new Database($con);

                // Insert Models
                $database->insertSql(null, [__DIR__ . DS . 'Config' . DS . 'thelia.sql']);
            } catch (\PDOException $exception) {
                $activate = false;
            }
        }

        return $activate;
    }

    public function postActivation(ConnectionInterface $con = null)
    {
        $configurationFileHandler = new ConfigurationFileHandler;

        $modules = ModuleQuery::create()->findByActivate(BaseModule::IS_ACTIVATED);
        /** @var Module $module */
        foreach ($modules as $module) {
            $configurationFileHandler->loadConfigurationFile($module);
        }
    }
}
