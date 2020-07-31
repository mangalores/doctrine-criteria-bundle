<?php

namespace Lores\Framework\CriteriaBundle\Test;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\Common\Collections\Expr\Comparison;
use Lores\Framework\CriteriaBundle\CriteriaBuilder;
use Lores\Framework\CriteriaBundle\Handler\CriteriaHandlerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

/**
 * @covers \Lores\Framework\CriteriaBundle\CriteriaBuilder
 */
final class CriteriaBuilderTest extends MockeryTestCase
{
    /**
     * @var CriteriaBuilder
     */
    private $builder;

    /**
     * @var CriteriaHandlerInterface|MockInterface
     */
    private $handler;

    protected function setUp(): void
    {
        $this->handler = Mockery::mock(CriteriaHandlerInterface::class);
        $this->builder = new CriteriaBuilder($this->handler);
    }

    public function testBuildWithDefault(): void
    {
        $this->handler
            ->shouldReceive('__invoke')
            ->with(Mockery::type(Criteria::class), 'foo', 'bar')
            ->once();

        $this->builder->build(['foo' => 'bar']);
    }

    public function testInvalidHandlerType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid handler parameter given. Must be of type \Closure or implement CriteriaHandlerInterface'
        );

        $this->builder->add('foo', Mockery::mock());
    }

    public function testBuildWithCustom(): void
    {
        $custom = Mockery::mock(CriteriaHandlerInterface::class);
        $custom
            ->shouldReceive('__invoke')
            ->with(Mockery::type(Criteria::class), 'foo', 'bar')
            ->once();

        $this->builder->add('foo', $custom);

        $this->builder->build(['foo' => 'bar']);
    }

    public function testBuildWithClosure(): void
    {
        $custom = static function (Criteria $criteria, $field, $filter) {
            /** @var ExpressionBuilder $expr */
            $expr = Criteria::expr();
            $criteria->where($expr->eq($field, $filter));

        };

        $this->builder->add('foo', $custom);

        /** @var Comparison $actual */
        $actual = $this->builder->build(['foo' => 'bar'])->getWhereExpression();

        static::assertInstanceOf(Comparison::class, $actual);
        static::assertEquals('foo', $actual->getField());
        static::assertEquals('=', $actual->getOperator());
        static::assertEquals('bar', $actual->getValue()->getValue());
    }
}
