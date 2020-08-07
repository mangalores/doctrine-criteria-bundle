<?php

namespace Lores\Doctrine\CriteriaBundle\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

final class DoctrineORMAdapter implements AdapterInterface
{
    private const DEFAULT_TYPE = 'string';

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var ClassMetadata
     */
    private $classMetaData;

    public function __construct(EntityManagerInterface $manager, ClassMetadata $classMetadata)
    {
        $this->manager = $manager;
        $this->classMetaData = $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function hasField(string $field): bool
    {
        return $this->classMetaData->hasField($field);
    }

    /**
     * {@inheritdoc}
     */
    public function convertArrayValue(string $field, array $values): array
    {
        $connection = $this->manager->getConnection();

        foreach ($values as $value) {
            $converted[] = $connection->convertToDatabaseValue(
                $this->convertValue($field, $value),
                $this->getType($field)
            );
        }

        return $converted ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function convertValue($field, $value)
    {
        return $this->manager->getConnection()->convertToPHPValue(
            $value,
            $this->getType($field)
        );
    }

    private function getType(string $field): string
    {
        return$this->classMetaData->getTypeOfField($field) ?? self::DEFAULT_TYPE;
    }
}
