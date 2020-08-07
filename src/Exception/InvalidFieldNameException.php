<?php

namespace Lores\Doctrine\CriteriaBundle\Exception;

final class InvalidFieldNameException extends FilterCriteriaException
{
    protected const MESSAGE = 'Unknown field name \'%s\' in filter';
}
