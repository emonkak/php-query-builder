<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\ExpressionInterface;

interface QueryInterface extends ExpressionInterface
{
    /**
     * @return string
     */
    public function __toString();
}
