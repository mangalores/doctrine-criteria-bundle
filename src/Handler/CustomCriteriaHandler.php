<?php

namespace Lores\Framework\CriteriaBundle\Handler;

use Closure;
use Doctrine\Common\Collections\Criteria;

final class CustomCriteriaHandler implements CriteriaHandlerInterface
{
    /**
     * @var Closure
     */
    private $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Criteria $criteria, string $field, $filter): void
    {
        ($this->closure)($criteria, $field, $filter);
    }
}
