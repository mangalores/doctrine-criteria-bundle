<?php

namespace Lores\Framework\CriteriaBundle\Exception;

final class InvalidFieldNameException extends FilterCriteriaException
{
    protected const MESSAGE = 'Unknown field name \'%s\' in filter';
}
