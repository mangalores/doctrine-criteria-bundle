<?php

namespace Lores\Doctrine\CriteriaBundle\Extractor;

interface SortExtractorInterface
{
    /**
     * @param mixed $sort
     */
    public function extract($sort): array;
}
