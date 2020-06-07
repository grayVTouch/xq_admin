<?php


namespace App\Http\Controllers\api\v1;


use Illuminate\Support\Facades\Hash;

class Test extends Base
{
    public function index()
    {
        var_dump(Hash::make('364793'));
    }
}
