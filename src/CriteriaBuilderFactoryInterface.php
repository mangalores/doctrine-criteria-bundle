<?php

namespace Lores\Doctrine\CriteriaBundle;

interface CriteriaBuilderFactoryInterface
{
    public function create(string $class): CriteriaBuilderInterface;
}
