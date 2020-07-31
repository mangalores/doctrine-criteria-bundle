<?php

namespace Lores\Framework\CriteriaBundle\Test\Extractor;

use Doctrine\Common\Collections\Expr\Comparison;
use Lores\Framework\CriteriaBundle\Adapter\AdapterInterface;
use Lores\Framework\CriteriaBundle\Exception\InvalidFieldNameException;
use Lores\Framework\CriteriaBundle\Exception\InvalidOperatorException;
use Lores\Framework\CriteriaBundle\Exception\InvalidValueException;
use Lores\Framework\CriteriaBundle\Extractor\FilterExtractor;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Framework\CriteriaBundle\Extractor\FilterExtractor
 */
final class FilterExtractorTest extends MockeryTestCase
{
    private const FIELD_1 = 'field1';
    private const SIMPLE_FILTER = 'filter';
    private const INVALID_FILTER = ['foo' => 'filter1'];
    private const INVALID_MULTI_FILTER = ['gt' => ['filter1'], 'lt' => 'filter2'];
    private const MULTI_FILTER = ['gt' => 'filter1', 'lt' => 'filter2'];
    private const MULTI_VALUE_FILTER = ['in' => 'filter1|filter2'];

    /**
     * @var Mockery\MockInterface|AdapterInterface
     */
    private $adapter;

    /**
     * @var FilterExtractor
     */
    private $extractor;

    protected function setUp(): void
    {
        $this->adapter = Mockery::mock(AdapterInterface::class);
        $this->extractor = new FilterExtractor($this->adapter);
    }

    public function testInvalidField(): void
    {
        $this->expectException(InvalidFieldNameException::class);

        $this->adapter
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->once()
            ->andReturnFalse();

        \iterator_to_array($this->extractor->extract(self::FIELD_1, self::SIMPLE_FILTER));
    }

    public function testInvalidOperator(): void
    {
        $this->expectException(InvalidOperatorException::class);

        $this->adapter
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->once()
            ->andReturnTrue();

        \iterator_to_array($this->extractor->extract(self::FIELD_1, self::INVALID_FILTER));
    }

    public function testSingleFilter(): void
    {
        $this->adapter
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->once()
            ->andReturnTrue();

        $actual = \iterator_to_array($this->extractor->extract(self::FIELD_1, self::SIMPLE_FILTER));
        static::assertCount(1, $actual);

        [$comparison] = $actual;

        self::assertComparison($comparison, self::FIELD_1, '=', self::SIMPLE_FILTER);
    }

    public function testInvalidArrayMultiFilter(): void
    {
        $this->expectException(InvalidValueException::class);

        $this->adapter
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->once()
            ->andReturnTrue();

        \iterator_to_array($this->extractor->extract(self::FIELD_1, self::INVALID_MULTI_FILTER));
    }

    public function testMultipleFilters(): void
    {
        $this->adapter
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->once()
            ->andReturnTrue();
        $this->adapter
            ->shouldReceive('convertValue')
            ->with(self::FIELD_1, 'filter1')
            ->once()
            ->andReturn('value1');

        $this->adapter
            ->shouldReceive('convertValue')
            ->with(self::FIELD_1, 'filter2')
            ->once()
            ->andReturn('value2');

        $actual = \iterator_to_array($this->extractor->extract(self::FIELD_1, self::MULTI_FILTER));

        static::assertCount(2,$actual);

        [$comparison1, $comparison2] = $actual;

        self::assertComparison($comparison1, self::FIELD_1, '>', 'value1');
        self::assertComparison($comparison2, self::FIELD_1, '<', 'value2');
    }

    public function testMultipleFilterValues(): void
    {
        $this->adapter
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->once()
            ->andReturnTrue();
        $this->adapter
            ->shouldReceive('convertArrayValue')
            ->with(self::FIELD_1, ['filter1', 'filter2'])
            ->once()
            ->andReturn(['value1', 'value2']);


        $actual = \iterator_to_array($this->extractor->extract(self::FIELD_1, self::MULTI_VALUE_FILTER));

        static::assertCount(1, $actual);

        [$comparison1] = $actual;

        self::assertComparison($comparison1, self::FIELD_1, 'IN', ['value1', 'value2']);
    }

    private static function assertComparison(Comparison $comparison, string $field, string $operator, $value): void
    {
        static::assertInstanceOf(Comparison::class, $comparison);
        static::assertSame($field, $comparison->getField());
        static::assertSame($operator, $comparison->getOperator());
        static::assertSame($value, $comparison->getValue()->getValue());
    }
}

