<?php

namespace Emonkak\QueryBuilder;

trait ToStringable
{
    /**
     * @return string
     */
    public function __toString()
    {
        list ($sql, $binds) = $this->compile();
        $format = str_replace(['%', '?'], ['%%', '%s'], $sql);
        $args = array_map(function($bind) { return var_export($bind, true); }, $binds);
        return vsprintf($format, $args);
    }

    /**
     * @return array (sql, binds)
     */
    abstract public function compile();
}
