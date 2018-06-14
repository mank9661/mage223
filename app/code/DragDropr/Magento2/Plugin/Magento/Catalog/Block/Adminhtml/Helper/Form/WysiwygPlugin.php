<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Plugin\Magento\Catalog\Block\Adminhtml\Helper\Form;

use \Magento\Catalog\Block\Adminhtml\Helper\Form\Wysiwyg;
use \DragDropr\Magento2\Api\Wysiwyg\PluginInterface;
use \Magento\Framework\View\LayoutInterface;

/**
 * Class WysiwygPlugin
 *
 * @package DragDropr\Magento2\Plugin\Magento\Catalog\Block\Adminhtml\Helper\Form
 */
class WysiwygPlugin
{
    /**
     * @var PluginInterface[]
     */
    private $additionalPlugins;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * ConfigPlugin constructor
     *
     * @param LayoutInterface $layout
     * @param PluginInterface[] $additionalPlugins
     */
    public function __construct(LayoutInterface $layout, array $additionalPlugins = [])
    {
        $this->layout = $layout;
        $this->additionalPlugins = $additionalPlugins;
    }

    /**
     * Get additional plugin buttons html
     *
     * @param Wysiwyg $subject
     * @return string
     */
    private function getAdditionalPluginButtons(Wysiwyg $subject)
    {
        $pluginButtons = '';

        foreach ($this->additionalPlugins as $plugin) {
            // Skip disabled plugins
            if (! $plugin->getConfig()->isEnabled()) {
                continue;
            }

            $config = $plugin->getPluginConfig();
            $options = ! empty($config['options']) ? $config['options'] : [];

            // Convert options
            foreach ($options as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['search']) && isset($value['subject'])) {
                        foreach ($value['search'] as $property) {
                            $value['subject'] = str_replace(
                                '{{' . $property . '}}',
                                $subject->getDataUsingMethod($property),
                                $value['subject']
                            );
                        }

                        $options[$key] = $value['subject'];
                    } else {
                        $options[$key] = '';
                    }
                }
            }

            $pluginButtons .= $this->layout->createBlock(
                'Magento\Backend\Block\Widget\Button',
                '',
                [
                    'data' => [
                        'label' => ! empty($options['title']) ? $options['title'] : '',
                        'type' => 'button',
                        'disabled' => ! empty($options['enabled']) ? ! $options['enabled'] : '',
                        'class' => ! empty($options['class']) ? $options['class'] : '',
                        'onclick' => ! empty($options['onclick']) ? $options['onclick'] : ''
                    ]
                ]
            )->toHtml();
        }

        return $pluginButtons;
    }

    /**
     * Always show plugin button on frontend - magento 2.0.x
     *
     * @param Wysiwyg $subject
     * @param $html
     * @return string
     */
    public function afterGetAfterElementHtml(Wysiwyg $subject, $html)
    {
        $pluginButtons = $this->getAdditionalPluginButtons($subject);
        return $html . $pluginButtons;
    }
}
