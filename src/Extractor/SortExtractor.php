<?php

namespace Lores\Framework\CriteriaBundle\Extractor;

use Doctrine\Common\Collections\Criteria;
use Lores\Framework\CriteriaBundle\Adapter\AdapterInterface;
use Lores\Framework\CriteriaBundle\Exception\InvalidDirectionException;
use Lores\Framework\CriteriaBundle\Exception\InvalidFieldNameException;
use Lores\Framework\CriteriaBundle\Exception\InvalidSortPatternException;

final class SortExtractor implements SortExtractorInterface
{
    private const DEFAULT_SORT_PATTERN = '/([a-zA-Z0-9-_]*):([a-zA-Z0-9-_]*)/';
    private const DEFAULT_DIRECTIONS = [Criteria::ASC, Criteria::DESC];

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string[]
     */
    private $directions;

    /**
     * @var string
     */
    private $pattern;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->directions = self::DEFAULT_DIRECTIONS;
        $this->pattern = self::DEFAULT_SORT_PATTERN;
    }

    /**
     * @param mixed $sort
     *
     * @return string[]
     */
    public function extract($sort): array
    {
        if (!is_array($sort)) {
            $sort = [$sort];
        }

        foreach ($sort as $pattern) {
            $matches = [];
            if (!preg_match($this->pattern, $pattern, $matches)) {
                throw new InvalidSortPatternException($pattern, $this->pattern);
            }

            [, $field, $direction] = $matches;

            if (!$this->adapter->hasField($field)) {
                throw new InvalidFieldNameException($field);
            }
            if (!is_string($direction) || !in_array(strtoupper($direction), $this->directions, true)) {
                throw new InvalidDirectionException($direction);
            }
            $orderings[$field] = strtoupper($direction);
        }

        return $orderings ?? [];
    }
}
