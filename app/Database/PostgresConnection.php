<?php

namespace App\Database;

use Illuminate\Database\PostgresConnection as BasePostgresConnection;

class PostgresConnection extends BasePostgresConnection
{
    /**
     * Prepare the query bindings for execution.
     * Overridden to fix PostgreSQL boolean handling.
     *
     * @return array
     */
    public function prepareBindings(array $bindings)
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
            if ($value instanceof \DateTimeInterface) {
                $bindings[$key] = $value->format($grammar->getDateFormat());
            } elseif (is_bool($value)) {
                // Use string 'true'/'false' instead of integer 1/0 for PostgreSQL boolean columns
                $bindings[$key] = $value ? 'true' : 'false';
            }
        }

        return $bindings;
    }
}
