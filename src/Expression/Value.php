<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

/**
 * @internal
 */
class Value implements QueryFragmentInterface
{
    use ExpressionHelper;

    /**
     * @var mixed $value
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return ['?', [$this->value]];
    }
}
