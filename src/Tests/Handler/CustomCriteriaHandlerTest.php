<?php

namespace Lores\Doctrine\CriteriaBundle\Tests\Handler;

use Doctrine\Common\Collections\Criteria;
use Lores\Doctrine\CriteriaBundle\Handler\CustomCriteriaHandler;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Doctrine\CriteriaBundle\Handler\CustomCriteriaHandler
 */
final class CustomCriteriaHandlerTest extends MockeryTestCase
{
    public function testHandler(): void
    {
        $handler = new CustomCriteriaHandler($this->createClosure());
        $criteria = new Criteria();
        $handler($criteria, 'foo', 'bar');

        static::assertEquals(Criteria::expr()->eq('foo', 'bar'), $criteria->getWhereExpression());
    }

    private function createClosure(): \Closure
    {
        return static function (Criteria $criteria, $field, $filter) {
            $criteria->andWhere(Criteria::expr()->eq($field, $filter));
        };
    }
}
