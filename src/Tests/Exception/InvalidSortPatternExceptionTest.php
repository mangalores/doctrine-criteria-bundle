<?php

namespace Lores\Doctrine\CriteriaBundle\Tests\Exception;

use Lores\Doctrine\CriteriaBundle\Exception\InvalidSortPatternException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Lores\Doctrine\CriteriaBundle\Exception\InvalidSortPatternException
 */
final class InvalidSortPatternExceptionTest extends MockeryTestCase
{
    private const PATTERN = 'FOO';
    private const CAUSE = 'BAR';

    public function testMessage(): void
    {
        $exception = new InvalidSortPatternException(self::PATTERN, self::CAUSE);

        static::assertSame(
            \sprintf(
                'sort pattern \'%s\' is invalid! (Expected: %s)',
                self::PATTERN,
                self::CAUSE
            ),
            $exception->getMessage()
        );
    }
}
