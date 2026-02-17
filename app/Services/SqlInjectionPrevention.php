<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SqlInjectionPrevention
{
    public static function sanitizeQuery($query): string
    {
        // Remove dangerous SQL keywords and patterns
        $dangerous = [
            'DROP', 'DELETE', 'TRUNCATE', 'ALTER', 'CREATE', 'INSERT', 'UPDATE',
            'EXEC', 'EXECUTE', 'UNION', 'SCRIPT', '--', '/*', '*/', ';'
        ];
        
        foreach ($dangerous as $keyword) {
            $query = preg_replace('/\b' . preg_quote($keyword, '/') . '\b/i', '', $query);
        }
        
        return trim($query);
    }

    public static function safeSearch($table, $column, $term, $limit = 50)
    {
        return DB::table($table)
            ->where($column, 'LIKE', '%' . addslashes($term) . '%')
            ->limit($limit)
            ->get();
    }

    public static function validateTableName($table): bool
    {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table) === 1;
    }

    public static function validateColumnName($column): bool
    {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column) === 1;
    }
}