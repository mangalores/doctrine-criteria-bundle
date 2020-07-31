<?php

namespace Lores\Framework\CriteriaBundle\Test\Adapter;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Lores\Framework\CriteriaBundle\Adapter\DoctrineORMAdapter;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Framework\CriteriaBundle\Adapter\DoctrineORMAdapter
 */
final class DoctrineORMAdapterTest extends MockeryTestCase
{
    private const FIELD_1 = 'field1';
    private const FIELD_2 = 'field2';

    /**
     * @var Mockery\MockInterface|ClassMetadata
     */
    private $classMetaData;

    /**
     * @var Mockery\MockInterface|EntityManagerInterface
     */
    private $manager;

    /**
     * @var DoctrineORMAdapter
     */
    private $adapter;

    protected function setUp(): void
    {
        $this->manager = Mockery::mock(EntityManagerInterface::class);
        $this->classMetaData = Mockery::mock(ClassMetadata::class);
        $this->adapter = new DoctrineORMAdapter($this->manager, $this->classMetaData);
    }

    public function testHasField(): void
    {
        $this->classMetaData
            ->shouldReceive('hasField')
            ->with(self::FIELD_1)
            ->andReturnFalse();

        $this->classMetaData
            ->shouldReceive('hasField')
            ->with(self::FIELD_2)
            ->andReturnTrue();

        static::assertFalse($this->adapter->hasField(self::FIELD_1));
        static::assertTrue($this->adapter->hasField(self::FIELD_2));
    }

    public function testConvertValue(): void
    {
        $connection = Mockery::mock(Connection::class);
        $connection
            ->shouldReceive('convertToPHPValue')
            ->with('foo', 'baz')
            ->once()
            ->andReturn('bar');

        $this->expandManager($connection);

        $this->classMetaData
            ->shouldReceive('getTypeOfField')
            ->with(self::FIELD_1)
            ->andReturn('baz');

        static::assertSame('bar', $this->adapter->convertValue(self::FIELD_1, 'foo'));
    }

    public function testConvertArrayValues(): void
    {
        $connection = Mockery::mock(Connection::class);
        $connection
            ->shouldReceive('convertToPHPValue')
            ->with(1, 'baz')
            ->once()
            ->andReturn('foo');
        $connection
            ->shouldReceive('convertToPHPValue')
            ->with(2, 'baz')
            ->once()
            ->andReturn('bar');
        $connection
            ->shouldReceive('convertToPHPValue')
            ->with(3, 'baz')
            ->once()
            ->andReturn('baz');
        $connection
            ->shouldReceive('convertToDatabaseValue')
            ->once()
            ->with('foo', 'baz')
            ->andReturn('foo');
        $connection
            ->shouldReceive('convertToDatabaseValue')
            ->once()
            ->with('bar', 'baz')
            ->andReturn('bar');
        $connection
            ->shouldReceive('convertToDatabaseValue')
            ->once()
            ->with('baz', 'baz')
            ->andReturn('baz');

        $this->classMetaData
            ->shouldReceive('getTypeOfField')
            ->with(self::FIELD_1)
            ->andReturn('baz');

        $this->expandManager($connection);

        static::assertSame(['foo', 'bar', 'baz'], $this->adapter->convertArrayValue(self::FIELD_1, [1, 2, 3]));

    }

    private function expandManager($connection): void
    {
        $this->manager
            ->shouldReceive('getConnection')
            ->andReturn($connection);
    }
}
