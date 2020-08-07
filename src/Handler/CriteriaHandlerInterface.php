<?php

namespace Lores\Doctrine\CriteriaBundle\Handler;

use Doctrine\Common\Collections\Criteria;

interface CriteriaHandlerInterface
{
    /**
     * @param mixed $filter
     */
    public function __invoke(Criteria $criteria, string $field, $filter): void;
}
