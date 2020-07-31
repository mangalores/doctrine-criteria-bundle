<?php

namespace Lores\Framework\CriteriaBundle;

interface CriteriaBuilderFactoryInterface
{
    public function create(string $class): CriteriaBuilderInterface;
}
