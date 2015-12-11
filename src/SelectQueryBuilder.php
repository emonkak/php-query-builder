<?php

namespace Emonkak\QueryBuilder;

use Emonkak\QueryBuilder\Compiler\CompilerInterface;
use Emonkak\QueryBuilder\Compiler\DefaultCompiler;

class SelectQueryBuilder implements QueryBuilderInterface
{
    use Chainable;
    use SelectQueryBuilderTrait;
    use ToStringable;
}
