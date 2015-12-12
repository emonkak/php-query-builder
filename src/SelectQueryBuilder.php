<?php

namespace Emonkak\QueryBuilder;

class SelectQueryBuilder implements QueryBuilderInterface
{
    use Chainable;
    use SelectQueryBuilderTrait;
    use ToStringable;
}
