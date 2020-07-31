<?php

namespace Lores\Framework\CriteriaBundle\Exception;

final class InvalidOperatorException extends FilterCriteriaException
{
    protected const MESSAGE = 'Unknown operator \'%s\' in filter';
}
