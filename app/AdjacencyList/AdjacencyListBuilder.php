<?php

namespace App\AdjacencyList;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AdjacencyListBuilder
{
    protected $builder;

    public function __construct(string $table, int $id)
    {
        $statement = "
            WITH RECURSIVE cte (id, parent_id, name, depth) AS
            (
                SELECT id, parent_id, name, 0 AS depth
                FROM %s
                WHERE parent_id = %d
                UNION ALL
                SELECT c.id, c.parent_id, c.name, ct.depth + 1
                FROM cte AS ct
                JOIN %s AS c ON (c.parent_id = ct.id)
            )
            SELECT * FROM cte;
        ";

        $sql = sprintf($statement, $table, $id, $table);
        $driver = config('database.default');
        $connection = DB::connection($driver);

        $this->builder = new Builder($connection, new AdjacencyListGrammar($sql));
    }

    public function get()
    {
        return $this->builder;
    }
}
