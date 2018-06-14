<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DragDropr\Magento2\Test\Unit;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var boolean
     */
    private static $createMockExists;

    /**
     * @var boolean
     */
    private static $createPartialMockExists;

    /**
     * @var boolean
     */
    private static $atMostExists;

    /**
     * Check whether method exists in parent class
     *
     * @param $methodName
     * @return bool
     */
    private static function originalMethodExists($methodName)
    {
        $parentClass = get_parent_class(self::class);
        return method_exists($parentClass, $methodName);
    }

    /**
     * Returns a test double for the specified class.
     *
     * @param string $originalClassName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     *
     * @throws \Exception
     */
    protected function createMock($originalClassName)
    {
        if (self::$createMockExists === null) {
            self::$createMockExists = $this->originalMethodExists('createMock');
        }

        // Call parent method if it exists
        if (self::$createMockExists) {
            return parent::createMock($originalClassName);
        }

        return $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->getMock();
    }

    /**
     * Returns a partial test double for the specified class.
     *
     * @param string   $originalClassName
     * @param string[] $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     *
     * @throws \Exception
     */
    protected function createPartialMock($originalClassName, array $methods)
    {
        if (self::$createPartialMockExists === null) {
            self::$createPartialMockExists = $this->originalMethodExists('createPartialMock');
        }

        // Call parent method if it exists
        if (self::$createPartialMockExists) {
            return parent::createMock($originalClassName, $methods);
        }

        return $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->setMethods(empty($methods) ? null : $methods)
            ->getMock();
    }

    /**
     * Returns a matcher that matches when the method is executed
     * at most N times.
     *
     * @param int $allowedInvocations
     *
     * @return \PHPUnit_Framework_MockObject_Matcher_InvokedAtMostCount
     */
    public static function atMost($allowedInvocations)
    {
        if (self::$atMostExists === null) {
            self::$atMostExists = self::originalMethodExists('atMost');
        }

        // Call parent method if it exists
        if (self::$atMostExists) {
            return parent::atMost($allowedInvocations);
        }

        return new \PHPUnit_Framework_MockObject_Matcher_InvokedAtMostCount(
            $allowedInvocations
        );
    }
}
