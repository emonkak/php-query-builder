<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

/**
 * @internal
 */
class NullValue implements QueryFragmentInterface
{
    use ExpressionHelper;

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return ['NULL', []];
    }
}
