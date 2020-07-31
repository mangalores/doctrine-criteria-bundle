<?php

namespace Lores\Framework\CriteriaBundle\Extractor;

interface SortExtractorInterface
{
    /**
     * @param mixed $sort
     */
    public function extract($sort): array;
}
