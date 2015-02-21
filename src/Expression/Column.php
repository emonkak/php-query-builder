<?php

namespace Emonkak\QueryBuilder\Expression;

class Column implements ExpressionInterface
{
    use ExpressionHelper;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $column;

    /**
     * @param string $table
     * @param string $column
     */
    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return ["`$this->table`.`$this->column`", []];
    }
}
