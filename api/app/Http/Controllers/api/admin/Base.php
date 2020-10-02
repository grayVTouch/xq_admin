<?php


namespace App\Http\Controllers\api\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ReflectionClass;

class Base extends Controller
{
    /**
     * @var Request
     */
    public $request;

    public function __construct(Request $request)
    {
        $reflection = new ReflectionClass(parent::class);
        if ($reflection->getConstructor()) {
            parent::__construct();
        }
        $this->request = $request;
    }
}
