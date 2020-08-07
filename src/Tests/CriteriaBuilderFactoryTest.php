<?php

namespace Lores\Doctrine\CriteriaBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Lores\Doctrine\CriteriaBundle\CriteriaBuilderFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Doctrine\CriteriaBundle\CriteriaBuilderFactory
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
