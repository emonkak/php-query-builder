<?php

namespace Emonkak\QueryBuilder\Expression;

use Emonkak\QueryBuilder\QueryFragmentInterface;

class Values implements QueryFragmentInterface
{
    use ExpressionHelpers;

    /**
     * @var mixed[]
     */
    private $values;

    /**
     * @var mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return [
            '(' . implode(', ', array_fill(0, count($this->values), '?')) . ')',
            $this->values
        ];
    }
}
