<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model;

use \DragDropr\Magento2\Api\IntegrationConfigInterface;

/**
 * Class IntegrationConfig
 *
 * @package DragDropr\Magento2\Model
 */
class IntegrationConfig implements IntegrationConfigInterface
{

    /**
     * @var \Magento\Integration\Model\ConsolidatedConfig|\Magento\Integration\Model\Config
     */
    private $integrationConfig;

    /**
     * @var \Magento\Integration\Model\IntegrationConfig
     */
    private $integrationAclConfig;

    /**
     * @var array
     */
    private $integrations;

    /**
     * @var array
     */
    private $aclConfig;

    /**
     * IntegrationConfig constructor
     */
    public function __construct()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        foreach ($this->getIntegrationConfigClassNames() as $className) {
            if (class_exists($className)) {
                $this->integrationConfig = $objectManager->get($className);
                break;
            }
        }

        foreach ($this->getIntegrationAclConfigClassNames() as $className) {
            if (class_exists($className)) {
                $this->integrationAclConfig = $objectManager->get($className);
                break;
            }
        }
    }

    /**
     * Get integration config class names in order of preference for backward compatibility
     *
     * @return array
     */
    private function getIntegrationConfigClassNames()
    {
        return [
            'Magento\Integration\Model\ConsolidatedConfig',
            'Magento\Integration\Model\Config'
        ];
    }

    /**
     * Get integration acl config class names in order of preference for backward compatibility
     *
     * @return array
     */
    private function getIntegrationAclConfigClassNames()
    {
        if (class_exists('Magento\Integration\Model\ConsolidatedConfig')) {
            return [];
        }

        return [
            'Magento\Integration\Model\IntegrationConfig'
        ];
    }

    /**
     * Get integration acl rules
     *
     * @param $integrationName
     * @return array
     */
    private function getIntegrationAclResources($integrationName)
    {
        if ($this->integrationAclConfig) {
            if ($this->aclConfig === null) {
                $integrationAclConfig = $this->integrationAclConfig->getIntegrations();

                if (! is_array($integrationAclConfig)) {
                    $integrationAclConfig = [];
                }

                $this->aclConfig = $integrationAclConfig;
            }

            if (! empty($this->aclConfig[$integrationName]['resources'])) {
                return $this->aclConfig[$integrationName]['resources'];
            }
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegrations()
    {
        if ($this->integrations !== null) {
            return $this->integrations;
        }

        $this->integrations = $this->integrationConfig->getIntegrations();

        if (! is_array($this->integrations)) {
            $this->integrations = [];
        }

        foreach ($this->integrations as $name => $config) {
            // Add acl resource to integration configuration
            if (! isset($config['resource'])) {
                $this->integrations[$name]['resource'] = $this->getIntegrationAclResources($name);
            }
        }

        return $this->integrations;
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegration($integrationName)
    {
        $integrationConfig = $this->getIntegrations();

        if (isset($integrationConfig[$integrationName])) {
            return $integrationConfig[$integrationName];
        }

        return null;
    }
}
