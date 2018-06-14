<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model\Wysiwyg\Plugin;

use \Magento\Backend\Model\UrlInterface;
use \Magento\Framework\View\Asset\Repository;
use \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDroprInterface;
use \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface;

/**
 * Class DragDropr
 *
 * @package DragDropr\Magento2\Model\Wysiwyg\Plugin
 */
class DragDropr implements DragDroprInterface
{
    /**
     * Plugin internal name
     */
    const PLUGIN_NAME = 'dragdropr';

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Repository
     */
    private $assetRepository;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * DragDropr constructor
     *
     * @param ConfigInterface $config
     * @param Repository $assetRepository
     * @param UrlInterface $url
     */
    public function __construct(
        ConfigInterface $config,
        Repository $assetRepository,
        UrlInterface $url
    ) {
        $this->config = $config;
        $this->assetRepository = $assetRepository;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::PLUGIN_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getWysiwygJsPluginSrc()
    {
        return $this->assetRepository->getUrl(
            'DragDropr_Magento2::wysiwyg/tiny_mce/plugins/dragdropr/editor_plugin.js'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getPluginConfig()
    {
        return [
            'name'    => $this->getName(),
            'src'     => $this->getWysiwygJsPluginSrc(),
            'options' => [
                'title'   => __('DragDropr'),
                'id'      => [
                    'search'  => ['html_id'],
                    'subject' => $this->getName() . '-{{html_id}}'
                ],
                'onclick' => [
                    'search'  => ['html_id'],
                    'subject' => 'window.DragDropr.execute(\'{{html_id}}\');'
                ],
                'class'   => 'open-dragdropr PluginInterface',//plugin disabled
                'enabled' => $this->config->isEnabled()
            ]
        ];
    }
}
