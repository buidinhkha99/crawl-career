<?php

namespace Outl1ne\NovaMediaHub\Filters;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Search
{
    public function handle($query, Closure $next)
    {
        $search = request()->get('search');

        if (empty($search)) {
            return $next($query);
        }

        $search = Str::lower($search);
        $search = Str::replace('*', '%', $search);
        $search = "%{$search}%";

        return $next($query)->where(
            function ($subQuery) use ($search) {
                $dataColumn = DB::raw('LOWER(data)'); // Mysql
                $subQuery->where(DB::raw('LOWER(file_name)'), 'LIKE', $search);

                if (config('database.default') === 'pgsql') {
                    // Postgres
                    $dataColumn = DB::raw('LOWER("data"::text)');
                }

                $subQuery->orWhere($dataColumn, 'LIKE', $search);

                return $subQuery;
            }
        );
    }
}
