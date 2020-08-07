<?php

namespace Lores\Doctrine\CriteriaBundle\Extractor;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Lores\Doctrine\CriteriaBundle\Adapter\AdapterInterface;
use Lores\Doctrine\CriteriaBundle\Exception\InvalidFieldNameException;
use Lores\Doctrine\CriteriaBundle\Exception\InvalidOperatorException;
use Lores\Doctrine\CriteriaBundle\Exception\InvalidValueException;

final class FilterExtractor implements FilterExtractorInterface
{
    private const ARRAY_OPERATORS = ['in', 'notIn'];
    private const DEFAULT_ARRAY_SEPARATOR = '|';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string
     */
    private $separator;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->separator = self::DEFAULT_ARRAY_SEPARATOR;
    }

    /**
     * {@inheritdoc}
     */
    public function extract(string $field, $filter): \Generator
    {
        if (!$this->adapter->hasField($field)) {
            throw new InvalidFieldNameException($field);
        }

        if (\is_array($filter)) {
            yield from $this->parse($field, $filter);

            return;
        }

        /** @var ExpressionBuilder $expr */
        $expr = Criteria::expr();

        yield $expr->eq($field, $filter);
    }

    private function parse(string $field, array $filter): \Generator
    {
        /** @var ExpressionBuilder $expr */
        $expr = Criteria::expr();

        foreach ($filter as $operator => $value) {
            if (!method_exists($expr, $operator)) {
                throw new InvalidOperatorException($operator);
            }
            if (is_array($value)) {
                throw new InvalidValueException('Array of values not allowed!');
            }
            if (\in_array($operator, self::ARRAY_OPERATORS, true)) {
                $values = explode($this->separator, $value) ?: [];
                yield $expr->$operator(
                    $field,
                    $this->adapter->convertArrayValue($field, $values)
                );

                continue;
            }

            yield $expr->$operator($field, $this->adapter->convertValue($field, $value));
        }
    }
}
