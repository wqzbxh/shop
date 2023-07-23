<?php
namespace App\Http\Controllers;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Controller
{
    public function pagePrinting(Request $request)
    {
        $user =  (new UserService())->getList($request);
        return view('page_printing/page_printing', ['users' => $user['data']['data']] );
    }
}

