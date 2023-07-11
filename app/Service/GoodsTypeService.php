<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Http\Resources\GoodsTypeResource;
use App\Mail\RegisterMail;
use App\Models\GoodsAttribute;
use App\Models\GoodsType;
use App\Models\menuModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GoodsTypeService
{

    protected $goodTypeDB;

    public function __construct()
    {
        $this->goodTypeDB = app(GoodsType::class);
    }
    public function createGoodType($request)
    {
        $this->goodTypeDB->name = $request->get('name');
        $this->goodTypeDB->sort = $request->get('sort');
        $this->goodTypeDB->is_use = $request->get('is_use') === true ? 1 : false ;
        $result = $this->goodTypeDB->save();   //添加记录失败
        if($result == false)
            return  MsgService::msg(20002, []);
        $newId = $this->goodTypeDB->id;  // 获取新添加的记录的 ID
        (new GoodsAttributeService())->create($request->get('attributes'),$newId);
        logsService::Logs('cj','创建了规格类型为'. $request->get('name').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, []);
    }

    public function getList($request)
    {
        $resource = CommonService::getList(GoodsType::class,$request);
        $goodtypeList = $resource['resource']->toArray();
        $returnArray=[];
        if(!empty($goodtypeList)){
            $ids = collect($goodtypeList)->pluck('id');
            $filteredAttributes = GoodsAttribute::whereIn('cid', $ids)->get();
            $attributes = $filteredAttributes->toArray();
            foreach ($goodtypeList as $item)
            {
                $itemArray = [];
                $itemArray['name'] = $item['name'];
                $itemArray['created_at'] = $item['created_at'];
                $itemArray['id'] = $item['id'];
                $itemArray['filteredAttributesItem']=[];
                foreach ($attributes as $attributesItem)
                {
                    if($item['id'] == $attributesItem['cid']){
                        array_push($itemArray['filteredAttributesItem'],$attributesItem);
                    }
                }
                array_push($returnArray,$itemArray);
            }

        }
        $data['count'] = $resource['count'];
        $data['data'] = $returnArray;
        return  MsgService::msg(200, $data);
    }
}
