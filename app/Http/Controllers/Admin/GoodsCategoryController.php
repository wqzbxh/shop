<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsCategoryRequset;
use App\Service\GoodsCategoryService;
use Illuminate\Http\Request;

class GoodsCategoryController extends Controller
{

    /**
     * 创建商品类型
     * @param GoodsCategoryRequset $request
     * @return \Illuminate\Support\Collection
     */
    public function goodsCategoryAction(GoodsCategoryRequset $request)
    {
        if($request->method()=== 'PUT')
            return  (new GoodsCategoryService())->update($request);

        return (new GoodsCategoryService())->create($request);
    }

    /**获取商品类型
     * @param Request $request
     * @return array
     */
    public function goodsCategoryList(Request $request)
    {
        return (new GoodsCategoryService())->getList($request);
    }
    /**获取商品类型
     * @param Request $request
     * @return array
     */
    public function goodsCategoryDelete(Request $request)
    {
         return  (new GoodsCategoryService())->delete($request);
    }

}
