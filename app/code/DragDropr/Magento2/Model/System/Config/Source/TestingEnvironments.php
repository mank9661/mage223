<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model\System\Config\Source;

use \Magento\Framework\Data\OptionSourceInterface;
use \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface;

class TestingEnvironments implements TestingEnvironmentsInterface, OptionSourceInterface
{
    /**
     * Get all available testing environments
     *
     * @return array
     */
    public function getEnvironments()
    {
        return [
            static::MODE_INTERNAL => __('Internal Mode'),
            static::MODE_EXTERNAL => __('External Mode'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $environments = [];

        foreach ($this->getEnvironments() as $environment => $label) {
            $environments[] = [
                'value' => $environment,
                'label' => $label
            ];
        }

        return $environments;
    }
}
