<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Clause\Alias;
use Emonkak\QueryBuilder\Clause\ConditionalJoin;
use Emonkak\QueryBuilder\Clause\Join;
use Emonkak\QueryBuilder\Clause\Sort;
use Emonkak\QueryBuilder\Clause\Union;
use Emonkak\QueryBuilder\Compiler\DefaultCompiler;
use Emonkak\QueryBuilder\QueryFragmentInterface;

trait SelectQueryBuilderTrait
{
    /**
     * @var string
     */
    private $prefix = 'SELECT';

    /**
     * @var QueryFragmentInterface[]
     */
    private $select = [];

    /**
     * @var QueryFragmentInterface[]
     */
    private $from = [];

    /**
     * @var QueryFragmentInterface[]
     */
    private $join = [];

    /**
     * @var QueryFragmentInterface
     */
    private $where = null;

    /**
     * @var QueryFragmentInterface[]
     */
    private $groupBy = [];

    /**
     * @var QueryFragmentInterface
     */
    private $having = null;

    /**
     * @var QueryFragmentInterface[]
     */
    private $orderBy = [];

    /**
     * @var integer
     */
    private $offset = null;

    /**
     * @var integer
     */
    private $limit = null;

    /**
     * @var string
     */
    private $suffix = null;

    /**
     * @var QueryFragmentInterface[]
     */
    private $union = [];

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getJoin()
    {
        return $this->join;
    }

    /**
     * @return QueryFragmentInterface
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getGroupBy()
    {
        return $this->groupBy;
    }

    /**
     * @return QueryFragmentInterface
     */
    public function getHaving()
    {
        return $this->having;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @return QueryFragmentInterface[]
     */
    public function getUnion()
    {
        return $this->union;
    }

    /**
     * @param QueryFragmentInterface[] $select
     * @return self
     */
    public function withSelect(array $select)
    {
        $chained = $this->chained();
        $chained->select = $select;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface[] $from
     * @return self
     */
    public function withFrom(array $from)
    {
        $chained = $this->chained();
        $chained->from = $from;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface[] $join
     * @return self
     */
    public function withJoin(array $join)
    {
        $chained = $this->chained();
        $chained->join = $join;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface $where
     * @return self
     */
    public function withWhere(QueryFragmentInterface $where = null)
    {
        $chained = $this->chained();
        $chained->where = $where;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface[] $groupBy
     * @return self
     */
    public function withGroupBy(array $groupBy)
    {
        $chained = $this->chained();
        $chained->groupBy = $groupBy;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface $having
     * @return self
     */
    public function withHaving(QueryFragmentInterface $having = null)
    {
        $chained = $this->chained();
        $chained->having = $having;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface[] $orderBy
     * @return self
     */
    public function withOrderBy(array $orderBy)
    {
        $chained = $this->chained();
        $chained->orderBy = $orderBy;
        return $chained;
    }

    /**
     * @param QueryFragmentInterface[] $union
     * @return self
     */
    public function withUnion(array $union)
    {
        $chained = $this->chained();
        $chained->union = $union;
        return $chained;
    }

    /**
     * @param string $prefix
     * @return self
     */
    public function prefix($prefix)
    {
        $chained = $this->chained();
        $chained->prefix = $prefix;
        return $chained;
    }

    /**
     * @param mixed  $expr
     * @param string $alias
     * @return self
     */
    public function select($expr, $alias = null)
    {
        $expr = Creteria::of($expr);
        if ($alias !== null) {
            $expr = new Alias($expr, $alias);
        }
        $select = $this->select;
        $select[] = $expr;
        return $this->withSelect($select);
    }

    /**
     * @param mixed  $expr
     * @param string $alias
     * @return self
     */
    public function from($expr, $alias = null)
    {
        $expr = Creteria::of($expr);
        if ($alias !== null) {
            $expr = new Alias($expr, $alias);
        }
        $from = $this->from;
        $from[] = $expr;
        return $this->withFrom($from);
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function where()
    {
        $args = func_get_args();
        $expr = Creteria::of($args);
        $where = $this->where ? $this->where->_and($expr) : $expr;
        return $this->withWhere($where);
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function orWhere()
    {
        $args = func_get_args();
        $expr = Creteria::of($args);
        $where = $this->where ? $this->where->_or($expr) : $expr;
        return $this->withWhere($where);
    }

    /**
     * @param mixed  $table
     * @param mixed  $condition
     * @param string $alias
     * @param string $type
     * @return self
     */
    public function join($table, $condition = null, $alias = null, $type = 'JOIN')
    {
        $table = Creteria::of($table);
        if ($alias !== null) {
            $table = new Alias($table, $alias);
        }
        $join = $this->join;
        if ($condition !== null) {
            $condition = Creteria::of($condition);
            $join[] = new ConditionalJoin($table, $condition, $type);
        } else {
            $join[] = new Join($table, $type);
        }
        return $this->withJoin($join);
    }

    /**
     * @param mixed  $table
     * @param mixed  $condition
     * @param string $alias
     * @return self
     */
    public function leftJoin($table, $condition = null, $alias = null)
    {
        return $this->join($table, $condition, $alias, 'LEFT JOIN');
    }

    /**
     * @param mixed  $expr
     * @param string $ordering
     * @return self
     */
    public function groupBy($expr, $ordering = null)
    {
        $expr = Creteria::of($expr);
        if ($ordering !== null) {
            $expr = new Sort($expr, $ordering);
        }
        $groupBy = $this->groupBy;
        $groupBy[] = $expr;
        return $this->withGroupBy($groupBy);
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function having()
    {
        $args = func_get_args();
        $expr = Creteria::of($args);
        $having = $this->having ? $this->having->_and($expr) : $expr;
        return $this->withHaving($having);
    }

    /**
     * @param mixed[] ...$args
     * @return self
     */
    public function orHaving()
    {
        $args = func_get_args();
        $expr = Creteria::of($args);
        $having = $this->having ? $this->having->_or($expr) : $expr;
        return $this->withHaving($having);
    }

    /**
     * @param mixed  $expr
     * @param stirng $ordering
     * @return self
     */
    public function orderBy($expr, $ordering = null)
    {
        $expr = Creteria::of($expr);
        if ($ordering !== null) {
            $expr = new Sort($expr, $ordering);
        }
        $orderBy = $this->orderBy;
        $orderBy[] = $expr;
        return $this->withOrderBy($orderBy);
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function limit($limit)
    {
        $chained = $this->chained();
        $chained->limit = $limit;
        return $chained;
    }

    /**
     * @param integer $integer
     * @return self
     */
    public function offset($offset)
    {
        $chained = $this->chained();
        $chained->offset = $offset;
        return $chained;
    }

    /**
     * @param string $suffix
     * @return self
     */
    public function suffix($suffix)
    {
        $chained = $this->chained();
        $chained->suffix = $suffix;
        return $chained;
    }

    /**
     * @return self
     */
    public function forUpdate()
    {
        return $this->suffix('FOR UPDATE');
    }

    /**
     * @param QueryBuilderInterface $query
     * @param string                $type
     * @return self
     */
    public function union(QueryBuilderInterface $query, $type = 'UNION')
    {
        $union = $this->union;
        $union[] = new Union($query, $type);
        return $this->withUnion($union);
    }

    /**
     * @param QueryBuilderInterface $query
     * @return self
     */
    public function unionAll(QueryBuilderInterface $query)
    {
        return $this->union($query, 'UNION ALL');
    }

    /**
     * @return array (string, mixed[])
     */
    public function build()
    {
        return $this->getCompiler()->compileSelect(
            $this->prefix,
            $this->select,
            $this->from,
            $this->join,
            $this->where,
            $this->groupBy,
            $this->having,
            $this->orderBy,
            $this->limit,
            $this->offset,
            $this->suffix,
            $this->union
        );
    }

    /**
     * @return CompilerInterface
     */
    protected function getCompiler()
    {
        return DefaultCompiler::getInstance();
    }

    /**
     * @return self
     */
    abstract protected function chained();
}
