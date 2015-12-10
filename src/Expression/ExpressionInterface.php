<?php

namespace Emonkak\QueryBuilder\Expression;

interface ExpressionInterface
{
    /**
     * @return array (string, mixed[])
     */
    public function build();
}
