<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model\Data;

class IntegrationtTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Api\IntegrationServiceInterface
     */
    protected $integrationServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Api\OauthServiceInterface
     */
    protected $oauthServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Model\Integration
     */
    protected $integrationMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\IntegrationConfigInterface
     */
    protected $integrationConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Data\EnvironmentInterface
     */
    protected $environmentMock;

    /**
     * @var \DragDropr\Magento2\Api\Data\IntegrationInterface
     */
    protected $integration;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Model\Data\Integration
     */
    protected $partialIntegration;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->integrationServiceMock = $this->createMock(\Magento\Integration\Api\IntegrationServiceInterface::class);
        $this->oauthServiceMock = $this->createMock(\Magento\Integration\Api\OauthServiceInterface::class);
        $this->integrationMock = $this->createMock(\Magento\Integration\Model\Integration::class);
        $this->integrationConfigMock = $this->createMock(\DragDropr\Magento2\Api\IntegrationConfigInterface::class);
        $this->environmentMock = $this->createMock(\DragDropr\Magento2\Api\Data\EnvironmentInterface::class);
        $this->integration = $this->objectManager->getObject(
            \DragDropr\Magento2\Model\Data\Integration::class,
            [
                'integrationService' => $this->integrationServiceMock,
                'oauthService' => $this->oauthServiceMock,
                'integration' => $this->integrationMock,
                'integrationConfig' => $this->integrationConfigMock,
                'environment' => $this->environmentMock
            ]
        );
        $this->partialIntegration = $this->getMockBuilder(\DragDropr\Magento2\Model\Data\Integration::class)
            ->setMethods(['getEndpoint', 'getIdentityLinkUrl', 'getDefaultConfig'])
            ->setConstructorArgs([
                'integrationService' => $this->integrationServiceMock,
                'oauthService' => $this->oauthServiceMock,
                'integration' => $this->integrationMock,
                'integrationConfig' => $this->integrationConfigMock,
                'environment' => $this->environmentMock
            ])
            ->getMock();
    }

    public function testGetId()
    {
        $this->integrationMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('integrationMock::getId');
        $this->assertSame(
            'integrationMock::getId',
            $this->integration->getId()
        );
    }

    public function testgetName()
    {
        $this->integrationMock
            ->expects($this->once())
            ->method('__call')
            ->with('getName')
            ->willReturn('integrationMock::getName');
        $this->assertSame(
            'integrationMock::getName',
            $this->integration->getName()
        );
    }

    public function testGetEmail()
    {
        $this->integrationMock
            ->expects($this->once())
            ->method('__call')
            ->with('getEmail')
            ->willReturn('integrationMock::getEmail');
        $this->assertSame(
            'integrationMock::getEmail',
            $this->integration->getEmail()
        );
    }

    public function dataForEndpoints()
    {
        return [
            [false, null],
            [false, false],
            [false, 'environmentEndpoint'],
            [true, null],
            [true, false],
            [true, 'environmentEndpoint']
        ];
    }

    /**
     * @dataProvider dataForEndpoints
     */
    public function testGetEndpoint($hasEnvironment, $environmentEndpoint)
    {
        $expectedValue = null;

        if ($hasEnvironment) {
            $this->environmentMock
                ->expects($this->atMost(2))
                ->method('getEndpoint')
                ->willReturn($environmentEndpoint);
            $expectedValue = $environmentEndpoint;

            if ($environmentEndpoint === false) {
                // Use partialMock to test getDefaultConfig and create testGetDefaultConfig
                $this->integrationMock
                    ->expects($this->once())
                    ->method('__call')
                    ->with('getName')
                    ->willReturn('integrationMock::getName');

                $this->integrationConfigMock
                    ->expects($this->once())
                    ->method('getIntegration')
                    ->with('integrationMock::getName')
                    ->willReturn([
                        'endpoint_url' => 'integrationConfigMock::getIntegration'
                    ]);
                $expectedValue = 'integrationConfigMock::getIntegration';
            }
        } else {
            $this->integrationMock
                ->expects($this->once())
                ->method('__call')
                ->with('getEndpoint')
                ->willReturn('integrationMock::getEndpoint');
            $expectedValue = 'integrationMock::getEndpoint';
        }

        $this->assertSame(
            $expectedValue,
            $this->integration->getEndpoint($hasEnvironment ? $this->environmentMock : null)
        );
    }

    /**
     * @dataProvider dataForEndpoints
     */
    public function testGetIdentityLinkUrl($hasEnvironment, $environmentEndpoint)
    {
        $expectedValue = null;

        if ($hasEnvironment) {
            $this->environmentMock
                ->expects($this->atMost(2))
                ->method('getIdentityLinkUrl')
                ->willReturn($environmentEndpoint);
            $expectedValue = $environmentEndpoint;

            if ($environmentEndpoint === false) {
                // Use partialMock to test getDefaultConfig and create testGetDefaultConfig
                $this->integrationMock
                    ->expects($this->once())
                    ->method('__call')
                    ->with('getName')
                    ->willReturn('integrationMock::getName');

                $this->integrationConfigMock
                    ->expects($this->once())
                    ->method('getIntegration')
                    ->with('integrationMock::getName')
                    ->willReturn([
                        'identity_link_url' => 'integrationConfigMock::getIntegration'
                    ]);
                $expectedValue = 'integrationConfigMock::getIntegration';
            }
        } else {
            $this->integrationMock
                ->expects($this->once())
                ->method('__call')
                ->with('getIdentityLinkUrl')
                ->willReturn('integrationMock::getIdentityLinkUrl');
            $expectedValue = 'integrationMock::getIdentityLinkUrl';
        }

        $this->assertSame(
            $expectedValue,
            $this->integration->getIdentityLinkUrl($hasEnvironment ? $this->environmentMock : null)
        );
    }

    public function testGetCreatedAt()
    {
        $this->integrationMock
            ->expects($this->once())
            ->method('__call')
            ->with('getCreatedAt')
            ->willReturn('integrationMock::getCreatedAt');
        $this->assertSame(
            'integrationMock::getCreatedAt',
            $this->integration->getCreatedAt()
        );
    }

    public function testGetUpdatedAt()
    {
        $this->integrationMock
            ->expects($this->once())
            ->method('__call')
            ->with('getUpdatedAt')
            ->willReturn('integrationMock::getUpdatedAt');
        $this->assertSame(
            'integrationMock::getUpdatedAt',
            $this->integration->getUpdatedAt()
        );
    }

    public function testGetEnvironment()
    {
        $this->assertSame(
            $this->environmentMock,
            $this->integration->getEnvironment()
        );
    }

    public function testDisable()
    {
        $this->integrationMock
            ->expects($this->at(0))
            ->method('__call')
            ->with('getConsumerId')
            ->willReturn('integrationMock::getConsumerId');
        $this->integrationMock
            ->expects($this->at(1))
            ->method('getId')
            ->willReturn('integrationMock::getId');
        $this->integrationMock
            ->expects($this->at(2))
            ->method('__call')
            ->with('getName')
            ->willReturn('integrationMock::getName');

        $this->oauthServiceMock
            ->expects($this->once())
            ->method('deleteIntegrationToken')
            ->with('integrationMock::getConsumerId');

        $this->partialIntegration
            ->expects($this->once())
            ->method('getDefaultConfig')
            ->with('resource')
            ->willReturn('partialIntegration::getDefaultConfig');

        $this->integrationServiceMock
            ->expects($this->once())
            ->method('update')
            ->with([
                'integration_id' => 'integrationMock::getId',
                'name' => 'integrationMock::getName',
                'status' => \DragDropr\Magento2\Api\Data\IntegrationInterface::STATUS_INACTIVE,
                'resource' => 'partialIntegration::getDefaultConfig'
            ])
            ->willReturn($this->integration);

        $this->assertSame(
            $this->partialIntegration,
            $this->partialIntegration->disable()
        );
        return $this;
    }

    public function testApplyEnvironment()
    {
        $this->integrationMock
            ->expects($this->at(0))
            ->method('getId')
            ->willReturn('integrationMock::getId');
        $this->integrationMock
            ->expects($this->at(1))
            ->method('__call')
            ->with('getName')
            ->willReturn('integrationMock::getName');
        $this->integrationMock
            ->expects($this->at(2))
            ->method('__call')
            ->with('getConsumerId')
            ->willReturn('integrationMock::getConsumerId');

        $this->partialIntegration
            ->expects($this->once())
            ->method('getEndpoint')
            ->with($this->environmentMock)
            ->willReturn('partialIntegration::getEndpoint');
        $this->partialIntegration
            ->expects($this->once())
            ->method('getIdentityLinkUrl')
            ->with($this->environmentMock)
            ->willReturn('partialIntegration::getIdentityLinkUrl');
        $this->partialIntegration
            ->expects($this->once())
            ->method('getDefaultConfig')
            ->with('resource')
            ->willReturn('partialIntegration::getDefaultConfig');

        $this->integrationServiceMock
            ->expects($this->once())
            ->method('update')
            ->with([
                'integration_id' => 'integrationMock::getId',
                'name' => 'integrationMock::getName',
                'endpoint' => 'partialIntegration::getEndpoint',
                'identity_link_url' => 'partialIntegration::getIdentityLinkUrl',
                'status' => \DragDropr\Magento2\Api\Data\IntegrationInterface::STATUS_INACTIVE,
                'resource' => 'partialIntegration::getDefaultConfig'
            ])
            ->willReturn($this->integration);
        $this->oauthServiceMock
            ->expects($this->once())
            ->method('deleteIntegrationToken')
            ->with('integrationMock::getConsumerId');

        $this->assertSame(
            $this->partialIntegration,
            $this->partialIntegration->applyEnvironment($this->environmentMock)
        );
        return $this;
    }
}
