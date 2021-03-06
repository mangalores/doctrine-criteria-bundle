<?php

namespace Lores\Doctrine\CriteriaBundle\Tests\Exception;

use Lores\Doctrine\CriteriaBundle\Exception\FilterCriteriaException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Doctrine\CriteriaBundle\Exception\FilterCriteriaException
 */
final class FilterCriteriaExceptionTest extends MockeryTestCase
{
    private const CAUSE = 'FOO';

    public function testException(): void
    {
        $exception = new FilterCriteriaException(self::CAUSE);

        static::assertSame(\sprintf('Invalid string in filter: \'%s\'', self::CAUSE), $exception->getMessage());
    }
}
