<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App;

// QueryLog class to help to debug DB queries
class QueryLog
{
    public function handle($request, Closure $next)
    {
        if (!App::environment('local') || !config('database.query_log')) {
            return $next($request);
        }
        DB::enableQueryLog();

        $return = $next($request);

        $queries = [url()->full()];
        $queryLog = DB::getQueryLog();
        $self = $this;

        foreach ($queryLog as $query) {
            $i = 0;

            $queries[] = preg_replace_callback('#\?#', function ($match) use (&$i, $query, $self) {
                if (!isset($query['bindings'][$i])) {
                    return '?';
                }
                return $self->bindingValue($query['bindings'][$i++]);
            }, $query['query']);
        }
        logger($queries);

        DB::disableQueryLog();

        return $return;
    }

    protected function bindingValue($binding)
    {
        $str = '';
        $type = gettype($binding);

        switch ($type) {
            case 'integer':
            case 'double':
                $str = '' . $binding;

                break;
            case 'string':
                $str = $binding;

                break;
            case 'object':
                $class = get_class($binding);

                switch ($class) {
                    case 'DateTime':
                        $str = $binding->format('Y-m-d H:i:s');

                        break;
                    default:
                        $str = '';
                }
                break;
            default:
                $str = '';
        }
        return DB::connection()->getPdo()->quote($str);
    }
}
