<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DbController extends Controller
{
    private $is_null;

    public function addColumn(Request $request)
    {

        $request->validate([
            'table_name' => 'required|min:1',
            'new_column_name' => 'required|min:1'
        ]);


        $table_name = $request->table_name;
        if (!$table_name) {
            return response()->json([
                'status' => false,
                'message' => 'Table not found'
            ], 404);
        }

        $new_column_name = $request->new_column_name;
        $column_default_value = $request->column_default_value;
        $this->is_null = $request->is_null;
        $data_type = $request->data_type;

        try {
            DB::statement("ALTER TABLE $table_name ADD $new_column_name $data_type $this->is_null?NULL:'' DEFAULT($column_default_value)");
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ]);

        } catch (HttpResponseException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }


    }
}
