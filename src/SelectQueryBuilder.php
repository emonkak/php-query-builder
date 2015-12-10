<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Compiler\CompilerInterface;
use Emonkak\QueryBuilder\Compiler\DefaultCompiler;

class SelectQueryBuilder implements QueryBuilderInterface
{
    use ToStringable;
    use SelectQueryBuilderTrait;

    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @param CompilerInterface $compiler
     * @return self
     */
    public static function create()
    {
        return new static(DefaultCompiler::getInstance());
    }

    /**
     * @param CompilerInterface $compiler
     */
    protected function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * {@inheritDoc}
     */
    public function build()
    {
        return $this->compiler->compileSelect(
            $this->prefix,
            $this->projections,
            $this->from,
            $this->join,
            $this->where,
            $this->groupBy,
            $this->having,
            $this->orderBy,
            $this->limit,
            $this->offset,
            $this->suffix
        );
    }
}
