<?php

namespace Lores\Doctrine\CriteriaBundle\Exception;

final class InvalidDirectionException extends FilterCriteriaException
{
    protected const MESSAGE = 'Invalid sort direction value: \'%s\'';
}
