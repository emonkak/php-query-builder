<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Expression\SubQuery;

class UnionQueryBuilder implements QueryBuilderInterface
{
    use ToStringable;

    /**
     * @var QueryBuilderInterface
     */
    private $lhs;

    /**
     * @var QueryBuilderInterface
     */
    private $rhs;

    /**
     * @var string
     */
    private $type;

    /**
     * @param QueryBuilderInterface $lhs
     * @param QueryBuilderInterface $rhs
     * @return self
     */
    public static function of(QueryBuilderInterface $lhs, QueryBuilderInterface $rhs)
    {
        return new static(
            $lhs instanceof self ? $lhs : new SubQuery($lhs),
            $rhs instanceof self ? $rhs : new SubQuery($rhs),
            'UNION'
        );
    }

    /**
     * @param QueryBuilderInterface $lhs
     * @param QueryBuilderInterface $rhs
     * @return self
     */
    public static function ofAll(QueryBuilderInterface $lhs, QueryBuilderInterface $rhs)
    {
        return new static(
            $lhs instanceof self ? $lhs : new SubQuery($lhs),
            $rhs instanceof self ? $rhs : new SubQuery($rhs),
            'UNION ALL'
        );
    }

    /**
     * @param QueryBuilderInterface $lhs
     * @param QueryBuilderInterface $rhs
     * @param string                $type
     */
    private function __construct(QueryBuilderInterface $lhs, QueryBuilderInterface $rhs, $type)
    {
        $this->lhs = $lhs;
        $this->rhs = $rhs;
        $this->type = $type;
    }

    /**
     * @param QueryBuilderInterface $rhs
     * @return UnionQueryBuilder
     */
    public function union(QueryBuilderInterface $rhs)
    {
        return self::of($this, $rhs);
    }

    /**
     * @param QueryBuilderInterface $rhs
     * @return UnionQueryBuilder
     */
    public function unionAll(QueryBuilderInterface $rhs)
    {
        return self::ofAll($this, $rhs);
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        list ($lhsSql, $lhsBinds) = $this->lhs->build();
        list ($rhsSql, $rhsBinds) = $this->rhs->build();
        return ["$lhsSql $this->type $rhsSql", array_merge($lhsBinds, $rhsBinds)];
    }
}
