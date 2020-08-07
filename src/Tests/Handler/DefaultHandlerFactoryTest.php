<?php

namespace Lores\Doctrine\CriteriaBundle\Tests\Handler;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Lores\Doctrine\CriteriaBundle\Adapter\AdapterInterface;
use Lores\Doctrine\CriteriaBundle\Handler\CustomCriteriaHandler;
use Lores\Doctrine\CriteriaBundle\Handler\DefaultHandlerFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Doctrine\CriteriaBundle\Handler\DefaultHandlerFactory
 */
final class DefaultHandlerFactoryTest extends MockeryTestCase
{
    private const FIELD_NAME = 'field';
    private const FIELD_VALUE = 'FOO';
    private const OFFSET = 252332;
    private const LIMIT = 578;
    private const SORT_EXPR = 'field:asc';
    private const SORT_RESULT = ['field' => 'ASC'];

    /**
     * @var Mockery\MockInterface|Criteria
     */
    private $criteria;

    /**
     * @var Mockery\MockInterface|AdapterInterface
     */
    private $adapter;

    protected function setUp(): void
    {
        $this->adapter = Mockery::mock(AdapterInterface::class);
        $this->adapter
            ->shouldReceive('hasField')
            ->andReturn(true);
        $this->criteria = Mockery::mock(Criteria::class);
    }

    public function testCreateDefaultHandler(): void
    {
        $handler = DefaultHandlerFactory::createDefaultFieldHandler($this->adapter);
        static::assertInstanceOf(CustomCriteriaHandler::class, $handler);

        $this->criteria
            ->shouldReceive('andWhere')
            ->with(Mockery::type(Expression::class))
            ->once();

        $handler($this->criteria, self::FIELD_NAME, self::FIELD_VALUE);
    }

    public function testLimitHandler(): void
    {
        $handler = DefaultHandlerFactory::createLimitHandler();
        static::assertInstanceOf(CustomCriteriaHandler::class, $handler);

        $this->criteria
            ->shouldReceive('setMaxResults')
            ->with(self::LIMIT)
            ->once();

        $handler($this->criteria, self::FIELD_NAME, self::LIMIT);
    }

    public function testOffsetHandler(): void
    {
        $handler = DefaultHandlerFactory::createOffsetHandler();
        static::assertInstanceOf(CustomCriteriaHandler::class, $handler);

        $this->criteria
            ->shouldReceive('setFirstResult')
            ->with(self::OFFSET)
            ->once();

        $handler($this->criteria, self::FIELD_NAME, self::OFFSET);
    }

    public function testSortHandler(): void
    {
        $handler = DefaultHandlerFactory::createSortHandler($this->adapter);
        static::assertInstanceOf(CustomCriteriaHandler::class, $handler);

        $this->criteria
            ->shouldReceive('orderBy')
            ->with(self::SORT_RESULT)
            ->once();

        $handler($this->criteria, self::FIELD_NAME, self::SORT_EXPR);
    }
}
