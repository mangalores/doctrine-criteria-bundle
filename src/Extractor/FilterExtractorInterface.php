<?php

namespace Lores\Doctrine\CriteriaBundle\Extractor;

interface FilterExtractorInterface
{
    /**
     * @param array|string $filter
     */
    public function extract(string $field, $filter): \Generator;
}
