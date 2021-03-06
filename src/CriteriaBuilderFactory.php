<?php

namespace Lores\Doctrine\CriteriaBundle;

use Doctrine\ORM\EntityManagerInterface;
use Lores\Doctrine\CriteriaBundle\Adapter\DoctrineORMAdapter;
use Lores\Doctrine\CriteriaBundle\Handler\DefaultHandlerFactory;

final class CriteriaBuilderFactory implements CriteriaBuilderFactoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $class): CriteriaBuilderInterface
    {
        $adapter = new DoctrineORMAdapter($this->manager, $this->manager->getClassMetadata($class));

        return (new CriteriaBuilder(DefaultHandlerFactory::createDefaultFieldHandler($adapter)))
            ->add('limit', DefaultHandlerFactory::createLimitHandler())
            ->add('offset', DefaultHandlerFactory::createOffsetHandler())
            ->add('sort', $sortHandler = DefaultHandlerFactory::createSortHandler($adapter))
            ->add('orderBy', $sortHandler);
    }
}
