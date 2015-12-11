<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

class NullValue implements QueryFragmentInterface
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
