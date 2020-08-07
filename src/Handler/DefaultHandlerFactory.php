<?php

namespace Lores\Doctrine\CriteriaBundle\Handler;

use Doctrine\Common\Collections\Criteria;
use Lores\Doctrine\CriteriaBundle\Adapter\AdapterInterface;
use Lores\Doctrine\CriteriaBundle\Extractor\FilterExtractor;
use Lores\Doctrine\CriteriaBundle\Extractor\SortExtractor;

final class DefaultHandlerFactory
{
    public static function createDefaultFieldHandler(AdapterInterface $adapter): CriteriaHandlerInterface
    {
        $extractor = new FilterExtractor($adapter);

        return new CustomCriteriaHandler(
            static function (Criteria $criteria, string $field, $filter) use ($extractor) {
                foreach ($extractor->extract($field, $filter) as $expr) {
                    $criteria->andWhere($expr);
                }
            }
        );
    }

    public static function createSortHandler(AdapterInterface $adapter): CriteriaHandlerInterface
    {
        $extractor = new SortExtractor($adapter);

        return new CustomCriteriaHandler(
            static function (Criteria $criteria, string $field, $filter) use ($extractor) {
                $criteria->orderBy($extractor->extract($filter));
            }
        );
    }

    public static function createLimitHandler(): CriteriaHandlerInterface
    {
        return new CustomCriteriaHandler(
            static function (Criteria $criteria, string $field, $filter) {
                $criteria->setMaxResults((int) $filter);
            }
        );
    }

    public static function createOffsetHandler(): CriteriaHandlerInterface
    {
        return new CustomCriteriaHandler(
            static function (Criteria $criteria, string $field, $filter) {
                $criteria->setFirstResult((int) $filter);
            }
        );
    }
}
