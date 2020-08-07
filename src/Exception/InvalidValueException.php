<?php

namespace Lores\Doctrine\CriteriaBundle\Exception;

final class InvalidValueException extends FilterCriteriaException
{
    protected const MESSAGE = 'Invalid filter value: \'%s\'';
}
