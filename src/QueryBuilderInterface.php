<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\ExpressionInterface;

interface QueryBuilderInterface extends ExpressionInterface
{
    /**
     * @return string
     */
    public function __toString();
}
