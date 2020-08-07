<?php

namespace Lores\Doctrine\CriteriaBundle\Adapter;

interface AdapterInterface
{
    public function hasField(string $field): bool;

    public function convertArrayValue(string $field, array $values): array;

    /**
     * @return mixed
     */
    public function convertValue(string $field, string $value);
}
