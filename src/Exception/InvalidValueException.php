<?php

namespace Lores\Framework\CriteriaBundle\Exception;

final class InvalidValueException extends FilterCriteriaException
{
    protected const MESSAGE = 'Invalid filter value: \'%s\'';
}
