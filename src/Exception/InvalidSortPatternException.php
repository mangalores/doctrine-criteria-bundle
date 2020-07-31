<?php

namespace Lores\Framework\CriteriaBundle\Exception;

final class InvalidSortPatternException extends FilterCriteriaException
{
    protected const MESSAGE = 'sort pattern \'%s\' is invalid! (Expected: %s)';

    /**
     * @var string
     */
    private $expected;

    public function __construct(string $pattern, string $expected)
    {
        $this->expected = $expected;

        parent::__construct($pattern);
    }

    protected function makeMessage(): string
    {
        return sprintf(self::MESSAGE, $this->cause, $this->expected);
    }
}
