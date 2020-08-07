<?php

namespace Lores\Doctrine\CriteriaBundle;

use Closure;
use Doctrine\Common\Collections\Criteria;
use Lores\Doctrine\CriteriaBundle\Handler\CriteriaHandlerInterface;

interface CriteriaBuilderInterface
{
    /**
     * @param CriteriaHandlerInterface|Closure $handler
     */
    public function add(string $field, $handler): CriteriaBuilderInterface;

    public function build(array $query): Criteria;
}
