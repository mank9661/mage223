<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Plugin\Magento\Integration\Model\Config\Consolidated;

use \Magento\Integration\Model\Config\Consolidated\Converter;
use \Magento\Framework\Acl\AclResource\ProviderInterface;

/**
 * Class ConverterPlugin
 *
 * @package DragDropr\Magento2\Plugin\Magento\Integration\Model\Config\Consolidated
 */
class ConverterPlugin
{
    /**
     * @var ProviderInterface
     */
    private $resourceProvider;

    /**
     * ConverterPlugin constructor
     *
     * @param ProviderInterface $resourceProvider
     */
    public function __construct(ProviderInterface $resourceProvider)
    {
        $this->resourceProvider = $resourceProvider;
    }

    /**
     * Prepend top level api resource so the integration are not reset without notable changes
     *
     * @param Converter $subject
     * @param array $result
     * @return array
     */
    public function afterConvert(Converter $subject, array $result)
    {
        $allResources = $this->resourceProvider->getAclResources();

        if (empty($allResources[1]) || empty($allResources[1]['id'])) {
            return $result;
        }

        // Is equal to Magento_Backend::admin
        $root = $allResources[1]['id'];

        foreach ($result as $integrationName => $integrationData) {
            if (! empty($integrationData[Converter::API_RESOURCES])) {
                if (! in_array($root, $integrationData[Converter::API_RESOURCES])) {
                    array_unshift($integrationData[Converter::API_RESOURCES], $root);
                    $result[$integrationName][Converter::API_RESOURCES] = $integrationData[Converter::API_RESOURCES];
                }
            }
        }

        return $result;
    }
}
