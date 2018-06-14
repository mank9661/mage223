<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model\System\Config\Source;


class TestingEnvironmentsTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface|\Magento\Framework\Data\OptionSourceInterface
     */
    protected $testingEnvironments;

    public function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->testingEnvironments = $this->objectManager->getObject(
            \DragDropr\Magento2\Model\System\Config\Source\TestingEnvironments::class
        );
    }

    public function testGetEnvironments()
    {
        $this->assertEquals(
            [
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL => __('Internal Mode'),
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL => __('External Mode')
            ],
            $this->testingEnvironments->getEnvironments()
        );
    }

    public function testToOptionArray()
    {
        $options = [];

        foreach ($this->testingEnvironments->getEnvironments() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        $this->assertEquals(
            $options,
            $this->testingEnvironments->toOptionArray()
        );
    }
}
