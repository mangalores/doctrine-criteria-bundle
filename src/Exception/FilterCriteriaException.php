<?php

namespace Lores\Framework\CriteriaBundle\Exception;

class FilterCriteriaException extends \InvalidArgumentException
{
    protected const MESSAGE = 'Invalid string in filter: \'%s\'';

    /**
     * @var string
     */
    protected $cause;

    public function __construct(string $cause)
    {
        $this->cause = $cause;
        parent::__construct($this->makeMessage());
    }

    protected function makeMessage(): string
    {
        return sprintf(self::MESSAGE, $this->cause);
    }
}
