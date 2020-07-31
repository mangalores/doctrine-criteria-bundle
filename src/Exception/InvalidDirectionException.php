<?php

namespace Lores\Framework\CriteriaBundle\Exception;

final class InvalidDirectionException extends FilterCriteriaException
{
    protected const MESSAGE = 'Invalid sort direction value: \'%s\'';
}
