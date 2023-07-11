<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsTypeRequest;
use App\Service\GoodsTypeService;
use Illuminate\Http\Request;

class GoodsTypeController extends Controller
{
    //
    public function typeAction(GoodsTypeRequest $request)
    {
        if($request->method()=== 'PUT')
            return  (new GoodsTypeService())->updateUser($request);

        return (new GoodsTypeService())->createGoodType($request);
    }
    public function getTypeList(Request $request)
    {
        return (new GoodsTypeService())->getList($request);
    }
}
