<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model\Data;

class EnvironmentTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Helper\Data
     */
    protected $dataHelperMock;

    /**
     * @var \DragDropr\Magento2\Model\Data\Environment
     */
    protected $environment;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected $environmentBuilder;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->scopeConfigMock = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->dataHelperMock = $this->createMock(\DragDropr\Magento2\Helper\Data::class);
        $this->environmentBuilder = $this->getMockBuilder(\DragDropr\Magento2\Model\Data\Environment::class)
            ->setConstructorArgs([
                'scopeConfig' => $this->scopeConfigMock,
                'dataHelper' => $this->dataHelperMock
            ]);
    }

    protected function buildData(array $first, array $second)
    {
        $data = [];

        foreach ($first as $fArguments) {
            foreach ($second as $sArguments) {
                $data[] = [$fArguments[0], $sArguments[0]];
            }
        }

        return $data;
    }

    public function dataForEnvironmentModes()
    {
        return [
            [\DragDropr\Magento2\Api\Data\EnvironmentInterface::MODE_PRODUCTION],
            [\DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL],
            [\DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL]
        ];
    }

    public function dataForIsDevelopment()
    {
        return [
            [false],
            [true]
        ];
    }

    public function dataForLinks()
    {
        return [
            ['not_valid_url'],
            ['http://valid-url.com'],
            ['https://secure-valid-url.com']
        ];
    }

    public function dataForEnvironmentAndLinks()
    {
        return $this->buildData($this->dataForEnvironmentModes(), $this->dataForLinks());
    }

    public function dataForGetDataHash()
    {
        return $this->buildData($this->dataForEnvironmentModes(), $this->dataForIsDevelopment());
    }

    /**
     * @dataProvider dataForIsDevelopment
     */
    public function testIsDevelopment($isDevelopment)
    {
        $this->scopeConfigMock
            ->expects($this->at(5))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::TESTING_ENABLED,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn($isDevelopment);

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode'])
            ->getMock();

        $this->assertSame(
            $isDevelopment,
            $environmentMock->isDevelopment()
        );
    }

    /**
     * @dataProvider dataForIsDevelopment
     */
    public function testGetMode($isDevelopment)
    {
        $this->scopeConfigMock
            ->expects($this->at(0))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::TESTING_MODE,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::TESTING_MODE');

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['isDevelopment'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('isDevelopment')
            ->willReturn($isDevelopment);

        $this->assertSame(
            $isDevelopment ? 'scopeConfigMock::TESTING_MODE' : \DragDropr\Magento2\Api\Data\EnvironmentInterface::MODE_PRODUCTION,
            $environmentMock->getMode()
        );
    }

    /**
     * @dataProvider dataForEnvironmentAndLinks
     */
    public function testGetIdentityLinkUrl($mode, $link)
    {
        $this->scopeConfigMock
            ->expects($this->at(1))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::IDENTITY_LINK,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn($link);

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('getMode')
            ->willReturn($mode);

        switch ($mode) {
            case \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL:
                $expectedValue = null;
                break;
            case \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL:
                $expectedValue = filter_var($link, FILTER_VALIDATE_URL) ? $link : false;
                break;
            default:
                $expectedValue = false;
        }

        $this->assertSame(
            $expectedValue,
            $environmentMock->getIdentityLinkUrl()
        );
    }

    /**
     * @dataProvider dataForEnvironmentAndLinks
     */
    public function testGetEndpoint($mode, $link)
    {
        $this->scopeConfigMock
            ->expects($this->at(2))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn($link);

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('getMode')
            ->willReturn($mode);

        switch ($mode) {
            case \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL:
                $expectedValue = null;
                break;
            case \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL:
                $expectedValue = filter_var($link, FILTER_VALIDATE_URL) ? $link : false;
                break;
            default:
                $expectedValue = false;
        }

        $this->assertSame(
            $expectedValue,
            $environmentMock->getEndpoint()
        );
    }

    /**
     * @dataProvider dataForEnvironmentModes
     */
    public function testGetPageEndpoint($mode)
    {
        $this->scopeConfigMock
            ->expects($this->at(3))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::PAGE_ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::PAGE_ENDPOINT');

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('getMode')
            ->willReturn($mode);

        if (in_array(
            $mode,
            [
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL,
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL
            ]
        )) {
            $expectedValue = 'scopeConfigMock::PAGE_ENDPOINT';
        } else {
            $expectedValue = null;
        }

        $this->assertSame(
            $expectedValue,
            $environmentMock->getPageEndpoint()
        );
    }

    /**
     * @dataProvider dataForEnvironmentModes
     */
    public function testGetCategoryEndpoint($mode)
    {
        $this->scopeConfigMock
            ->expects($this->at(4))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::CATEGORY_ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::CATEGORY_ENDPOINT');

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('getMode')
            ->willReturn($mode);

        if (in_array(
            $mode,
            [
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL,
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL
            ]
        )) {
            $expectedValue = 'scopeConfigMock::CATEGORY_ENDPOINT';
        } else {
            $expectedValue = null;
        }

        $this->assertSame(
            $expectedValue,
            $environmentMock->getCategoryEndpoint()
        );
    }

    /**
     * @dataProvider dataForGetDataHash
     */
    public function testGetDataHash($mode, $isDevelopment)
    {
        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode', 'isDevelopment', 'getEndpoint', 'getIdentityLinkUrl'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('isDevelopment')
            ->willReturn($isDevelopment);
        $environmentMock
            ->expects($this->atMost(2))
            ->method('getMode')
            ->willReturn($mode);

        $expectedData = [$mode];

        if ($isDevelopment && $mode === \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL) {
            $environmentMock
                ->expects($this->once())
                ->method('getIdentityLinkUrl')
                ->willReturn('environmentMock::getIdentityLinkUrl');
            $environmentMock
                ->expects($this->once())
                ->method('getEndpoint')
                ->willReturn('environmentMock::getEndpoint');

            $expectedData[] = 'environmentMock::getIdentityLinkUrl';
            $expectedData[] = 'environmentMock::getEndpoint';
        }

        $this->dataHelperMock
            ->expects($this->once())
            ->method('generateDataHash')
            ->with($expectedData)
            ->willReturn('dataHelperMock::generateDataHash');

        $this->assertSame(
            'dataHelperMock::generateDataHash',
            $environmentMock->getDataHash()
        );
    }

    /**
     * @dataProvider dataForEnvironmentModes
     */
    public function testGetDefaultEndpoint($mode)
    {
        $this->scopeConfigMock
            ->expects($this->at(6))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::DEFAULT_ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::DEFAULT_ENDPOINT');

        /**
         * @var $environmentMock \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Environment
         */
        $environmentMock = $this->environmentBuilder
            ->setMethods(['getMode'])
            ->getMock();
        $environmentMock
            ->expects($this->once())
            ->method('getMode')
            ->willReturn($mode);

        if (in_array(
            $mode,
            [
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_INTERNAL,
                \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface::MODE_EXTERNAL
            ]
        )) {
            $expectedValue = 'scopeConfigMock::DEFAULT_ENDPOINT';
        } else {
            $expectedValue = null;
        }

        $this->assertSame(
            $expectedValue,
            $environmentMock->getDefaultEndpoint()
        );
    }
}
