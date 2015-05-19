<?php

namespace CustomerGroup\Handler;

use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Model\CustomerGroupQuery;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Util\XmlUtils;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Thelia\Model\Module;

/**
 * Handle group configuration files
 */
class ConfigurationFileHandler
{
    /**
     * Find, parse and load customer group configuration file for module
     *
     * @param Module $module A module object
     *
     * @throws InvalidConfigurationException
     */
    public function loadConfigurationFile(Module $module)
    {
        $finder = (new Finder)
            // TODO: Flip when yaml parsing will be up
//            ->name('#customer[-_\.]?group\.(?:xml|yml|yaml)#i')
            ->name('#customer[-_\.]?group\.xml#i')
            ->in($module->getAbsoluteConfigPath())
        ;
        $count = $finder->count();

        if ($count > 1) {
            throw new InvalidConfigurationException('Too many configuration file.');
        } else {
            foreach ($finder as $file) {
                if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'xml') {
                    $moduleConfig = $this->parseXml($file);
                } else {
                    $moduleConfig = $this->parseYml($file);
                }

                $this->applyConfig($moduleConfig);
            }
        }
    }

    /**
     * Get config from xml file
     *
     * @param SplFileInfo $file XML file
     *
     * @return array Customer group module configuration
     */
    protected function parseXml(SplFileInfo $file)
    {
        $dom = XmlUtils::loadFile($file, realpath(dirname(__DIR__) . DS . 'Schema' . DS . 'customer-group.xsd'));
        /** @var \Symfony\Component\DependencyInjection\SimpleXMLElement $xml */
        $xml = simplexml_import_dom($dom, '\\Symfony\\Component\\DependencyInjection\\SimpleXMLElement');

        $parsedConfig = [];
        /** @var \Symfony\Component\DependencyInjection\SimpleXMLElement $customerGroupDefinition */
        foreach ($xml->customergroup as $customerGroupDefinition) {
            $descriptive = [];
            /** @var \Symfony\Component\DependencyInjection\SimpleXMLElement $descriptiveDefinition */
            foreach ($customerGroupDefinition->descriptive as $descriptiveDefinition) {
                $descriptive[] = [
                    'locale' => $descriptiveDefinition->getAttributeAsPhp('locale'),
                    'title' => (string) $descriptiveDefinition->title,
                    'description' => (string) $descriptiveDefinition->description
                ];
            }

            $parsedConfig['customer_group'][] = [
                'code' => $customerGroupDefinition->getAttributeAsPhp('code'),
                'descriptive' => $descriptive
            ];
        }

        $parsedConfig['default'] = (string) $xml->default;

        return $parsedConfig;
    }

    /**
     * Get config from yml file
     *
     * @param SplFileInfo $file Yaml file
     *
     * @return array Customer group module configuration
     */
    protected function parseYml(SplFileInfo $file)
    {
        //@todo

        return [];
    }

    /**
     * Save new customer group to database
     *
     * @param array $moduleConfiguration Customer group module configuration
     */
    protected function applyConfig(array $moduleConfiguration)
    {
        foreach ($moduleConfiguration['customer_group'] as $customerGroupData) {
            if (CustomerGroupQuery::create()->findOneByCode($customerGroupData['code']) === null) {
                $customerGroup = (new CustomerGroup)
                    ->setCode($customerGroupData['code'])
                ;

                foreach ($customerGroupData['descriptive'] as $descriptiveData) {
                    $customerGroup
                        ->setLocale($descriptiveData['locale'])
                        ->setTitle($descriptiveData['title'])
                        ->setDescription($descriptiveData['description'])
                    ;
                }

                $customerGroup->save();
            }
        }

        if ($moduleConfiguration['default']) {
            $customerGroup = CustomerGroupQuery::create()->findOneByCode($moduleConfiguration['default']);
            if ($customerGroup !== null) {
                $this->resetDefault();

                $customerGroup
                    ->setIsDefault(true)
                    ->save()
                ;
            }
        }
    }

    /**
     * Remove is_default flag
     */
    protected function resetDefault()
    {
        CustomerGroupQuery::create()
            ->filterByIsDefault(true)
            ->update(
                [
                    'IsDefault' => false
                ]
            )
        ;
    }
}
