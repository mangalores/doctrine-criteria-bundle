<?php

namespace Lores\Doctrine\CriteriaBundle\Tests\Extractor;

use Lores\Doctrine\CriteriaBundle\Adapter\AdapterInterface;
use Lores\Doctrine\CriteriaBundle\Exception\InvalidDirectionException;
use Lores\Doctrine\CriteriaBundle\Exception\InvalidFieldNameException;
use Lores\Doctrine\CriteriaBundle\Exception\InvalidSortPatternException;
use Lores\Doctrine\CriteriaBundle\Extractor\SortExtractor;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Doctrine\CriteriaBundle\Extractor\SortExtractor
 */
final class SortExtractorTest extends MockeryTestCase
{
    /**
     * @var Mockery\MockInterface|AdapterInterface
     */
    private $adapter;

    /**
     * @var SortExtractor
     */
    private $extractor;

    protected function setUp(): void
    {
        $this->adapter = Mockery::mock(AdapterInterface::class);
        $this->extractor = new SortExtractor($this->adapter);
    }


    /**
     * @param mixed       $sortExpr
     * @param array       $orderings
     * @param string|null $exception
     * @param bool|null   $hasField
     *
     * @dataProvider provideSortCases
     */
    public function testValidateSortExpression(
        $sortExpr,
        array $orderings = [],
        string $exception = null,
        bool $hasField = true
    ): void {
        if (null !== $exception) {
            $this->expectException($exception);
        }

        $this->adapter
            ->shouldReceive('hasField')
            ->andReturn($hasField);

        static::assertSame($orderings, $this->extractor->extract($sortExpr));
    }

    public function provideSortCases(): array
    {
        return [
            ['field1:desc', ['field1' => 'DESC']],
            ['field1:asc', ['field1' => 'ASC']],
            ['field1', [], InvalidSortPatternException::class],
            ['field1:foo', [], InvalidDirectionException::class],
            ['field1:foo', [], InvalidFieldNameException::class, false],
            ['field1:DESC', ['field1' => 'DESC']],
            ['field1:ASC', ['field1' => 'ASC']],
            ['field1:Desc', ['field1' => 'DESC']],
            ['field1:aSc', ['field1' => 'ASC']],
            [['field1:aSc', 'field2:desc'], ['field1' => 'ASC', 'field2' => 'DESC']],
        ];
    }
}
