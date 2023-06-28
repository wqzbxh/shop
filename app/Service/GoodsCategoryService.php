<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Http\Resources\GoodsCategoryResource;
use App\Mail\RegisterMail;
use App\Models\GoodsCategory;
use App\Models\menuModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GoodsCategoryService
{

    protected $goodsCategoryDB;

    public function __construct()
    {
        $this->goodsCategoryDB = app(GoodsCategory::class);
    }

    /**
     * 获取角色的菜单信息
     * @param $role_id
     * @param $type
     *
     * @return \Illuminate\Support\Collection
     */
    public  function  create($request)
    {
//        处理normal类型
        if($request->get('type') == 'normal'){};

//        获取参数,存储信息
        $this->goodsCategoryDB->type = $request->get('type');
        $this->goodsCategoryDB->category_name = $request->get('category_name');
        $this->goodsCategoryDB->order = $request->get('order');
        $this->goodsCategoryDB->category_desc = $request->get('category_desc');
        $result = $this->goodsCategoryDB->save();
        //添加记录失败
        if($result == false) return  MsgService::msg(20002, []);

        logsService::Logs('cj','创建了商品类型为'. $request->get('category_name').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, []);

    }

    /**
     * @return void
     */
    public function update($request)
    {
        //构建修改条件
        $updateCondition = [];
        if (empty($request->get('id')))
            return MsgService::msg(31000, []);
         $where = ['id' => $request->get('id')];

         $originValusArr = CommonService::getOne(GoodsCategory::class,$where);
         $request->get('order') ? $updateCondition['order']  = $request->get('order') : [];
         $request->get('category_desc') ? $updateCondition['category_desc']  = $request->get('category_desc') : [];
         $request->get('category_name') ?  $updateCondition['category_name']  = $request->get('category_name') : [];
         $request->get('type') ?$updateCondition['type']  = $request->get('type')  : [];

        $result = GoodsCategory::where($where)->update($updateCondition);
        if(!$result)
            return MsgService::msg(20015, []);

        ModificationlogsService::ModificationLog(GoodsCategory::class,$request->get('id'),$updateCondition,$originValusArr);
        logsService::Logs('gx','修改商品类型为'.$originValusArr['category_name'].'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return MsgService::msg(200, []);

    }
    public function getList($request)
    {
        $where = ['is_del' => 0 ];
        $resources = $this->goodsCategoryDB::where($where)->get();
        $data =GoodsCategoryResource::collection($resources);
        logsService::Logs('cx','查询商品类型',$request->url(),$request->method(),$request->getContent(),200, serialize($data));
        return  MsgService::msg(200, $data);
    }

    /**
     * @param $request
     * @return array
     */

    public function delete($request)
    {
        if (empty($request->get('id')))
            return MsgService::msg(31000, []);
        $where = ['id' => $request->get('id')];
        $originValusArr = CommonService::getOne(GoodsCategory::class,$where);
        $updateCondition['is_del'] = 1;
        $result = GoodsCategory::where($where)->update($updateCondition);
        if(!$result){
            return MsgService::msg(20015, []);
        }
        ModificationlogsService::ModificationLog(GoodsCategory::class,$request->get('id'),$updateCondition,$originValusArr);
        logsService::Logs('sc','对商品类型ID为'.$request->get('id').'的记录进行删除',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200,$originValusArr);
    }
}
