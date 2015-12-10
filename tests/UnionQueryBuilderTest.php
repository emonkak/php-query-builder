<?php

namespace Emonkak\QueryBuilder\Tests;

use Emonkak\QueryBuilder\SelectQueryBuilder;
use Emonkak\QueryBuilder\UnionQueryBuilder;

class UnionQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testUnion()
    {
        $q1 = SelectQueryBuilder::create()->select('c1')->from('t1')->where('c1', 'foo');
        $q2 = SelectQueryBuilder::create()->select('c1')->from('t1')->where('c1', 'bar');
        list ($sql, $binds) = UnionQueryBuilder::of($q1, $q2)->build();
        $this->assertSame('(SELECT c1 FROM t1 WHERE (c1 = ?)) UNION (SELECT c1 FROM t1 WHERE (c1 = ?))', $sql);
        $this->assertSame(['foo', 'bar'], $binds);

        $q1 = SelectQueryBuilder::create()->select('c1')->from('t1')->where('c1', 'foo');
        $q2 = SelectQueryBuilder::create()->select('c1')->from('t1')->where('c1', 'bar');
        $q3 = SelectQueryBuilder::create()->select('c1')->from('t1')->where('c1', 'baz');
        list ($sql, $binds) = UnionQueryBuilder::ofAll($q1, $q2)->unionAll($q3)->build();
        $this->assertSame('(SELECT c1 FROM t1 WHERE (c1 = ?)) UNION ALL (SELECT c1 FROM t1 WHERE (c1 = ?)) UNION ALL (SELECT c1 FROM t1 WHERE (c1 = ?))', $sql);
        $this->assertSame(['foo', 'bar', 'baz'], $binds);
    }
}
