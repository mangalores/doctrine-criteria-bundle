<?php

namespace Lores\Doctrine\CriteriaBundle;

use Closure;
use Doctrine\Common\Collections\Criteria;
use Lores\Doctrine\CriteriaBundle\Handler\CriteriaHandlerInterface;
use Lores\Doctrine\CriteriaBundle\Handler\CustomCriteriaHandler;

final class CriteriaBuilder implements CriteriaBuilderInterface
{
    /**
     * @var CriteriaHandlerInterface[]
     */
    private $stack = [];

    /**
     * @var CriteriaHandlerInterface
     */
    private $default;

    public function __construct(CriteriaHandlerInterface $default)
    {
        $this->default = $default;
    }

    /**
     * {@inheritdoc}
     */
    public function add(string $field, $handler): CriteriaBuilderInterface
    {
        if ($handler instanceof Closure) {
            $handler = new CustomCriteriaHandler($handler);
        }
        if (!$handler instanceof CriteriaHandlerInterface) {
            throw new \InvalidArgumentException(
                'Invalid handler parameter given. Must be of type \Closure or implement CriteriaHandlerInterface'
            );
        }

        $this->stack[$field] = $handler;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(array $query): Criteria
    {
        $criteria = new Criteria();

        foreach ($query as $field => $filter) {
            if (array_key_exists($field, $this->stack)) {
                $this->stack[$field]($criteria, $field, $filter);
                continue;
            }

            ($this->default)($criteria, $field, $filter);
        }

        return $criteria;
    }
}
