<?php

namespace Lores\Framework\CriteriaBundle\Test;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Lores\Framework\CriteriaBundle\CriteriaBuilderFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Framework\CriteriaBundle\CriteriaBuilderFactory
 */
final class CriteriaBuilderFactoryTest extends MockeryTestCase
{
    private const CLASS_NAME = 'Foo';

    public function testCreate(): void
    {
        $metaData = Mockery::mock(ClassMetadata::class);
        $manager = Mockery::mock(EntityManagerInterface::class);
        $manager
            ->shouldReceive('getClassMetaData')
            ->once()
            ->with(self::CLASS_NAME)
        ->andReturn($metaData);

        $factory = new CriteriaBuilderFactory($manager);

        $factory->create(self::CLASS_NAME);
    }
}
