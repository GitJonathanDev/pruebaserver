<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = [];

      
        $excludeTables = ['migrations', 'visitaspagina', 'sessions', 'cache', 'cache_locks', 'failed_jobs', 'jobs', 'jobs_batches', 'menu'];

        if ($query) {
      
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = $table->{'Tables_in_' . env('DB_DATABASE')};

 
                if (in_array($tableName, $excludeTables)) {
                    continue;
                }


                $columns = DB::select('SHOW COLUMNS FROM ' . $tableName);

                foreach ($columns as $column) {
                    $columnName = $column->Field;

                    $searchResults = DB::table($tableName)
                        ->where($columnName, 'LIKE', '%' . $query . '%')
                        ->get([$columnName]);

                    if ($searchResults->isNotEmpty()) {
                        $results[$tableName][$columnName] = $searchResults;
                    }
                }
            }
        }


        return response()->json($results);
    }
}
