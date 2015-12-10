<?php

namespace Emonkak\QueryBuilder\Expression;

class NullValue implements ExpressionInterface
{
    use ExpressionHelpers;

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return ['NULL', []];
    }
}
