<?php

namespace Emonkak\QueryBuilder\Expression;

class Table implements ExpressionInterface, \ArrayAccess
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var string|null
     */
    private $alias;

    /**
     * @param string      $table
     * @param string|null $alias
     */
    public function __construct($table, $alias = null)
    {
        $this->table = $table;
        $this->alias = $alias;
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return new Column(
            $this->alias !== null ? $this->alias : $this->table,
            $offset
        );
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('Unsupported operation.');
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        throw new \BadMethodCallException('Unsupported operation.');
    }

    /**
     * @see \ArrayAccess
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('Unsupported operation.');
    }

    /**
     * {@inheritDoc}
     */
    public function compile()
    {
        return [
            $this->alias !== null ? "`$this->table` AS `$this->alias`" : "`$this->table`",
            []
        ];
    }
}
