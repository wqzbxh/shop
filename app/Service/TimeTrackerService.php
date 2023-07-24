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
use App\Models\GoodsAttributeValue;
use App\Models\GoodsType;
use App\Models\menuModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Exception;

class TimeTrackerService
{

    protected $goodTypeDB;

    public function __construct()
    {
        $this->goodTimeTrackerDB = app(GoodsType::class);
    }
    public function create($request)
    {
        $this->goodTimeTrackerDB->name = $request->get('name');
        $this->goodTimeTrackerDB->sort = $request->get('sort');
        $this->goodTimeTrackerDB->is_use = $request->get('is_use') === true ? 1 : false ;
        $result = $this->goodTimeTrackerDB->save();   //添加记录失败
        if($result == false)
            return  MsgService::msg(20002, []);
        $newId = $this->goodTimeTrackerDB->id;  // 获取新添加的记录的 ID
        (new GoodsAttributeService())->create($request->get('attributes'),$newId);
        logsService::Logs('cj','创建了规格类型为'. $request->get('name').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, []);
    }

    /**
     * @param $request
     * @return array
     * 获取产品规格默
     */
    public function getList($request)
    {
        $type = $this->goodTypeDB->name = $request->get('type');
//        当没有指定type值得时候以分页形式返回
        if($type == 'select'){
//            当为select为下拉款返回全部
            $data = [];
            $selectRow = GoodsType::where('is_del','=','0')->select('id','name')->get();
            if(!empty($selectRow)){
                $data = $selectRow->toArray();
            }
            logsService::Logs('cx','请求商品规格下拉框-类型为'. $request->get('type').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
            return  MsgService::msg(200, $data);
        }
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


    public function getTypeAttribute($request)
    {
//        获取id
        $id = $this->goodTypeDB->name = $request->get('id');
        if(empty($id))  return  MsgService::msg(31001,[] );
//        获取这个类型下面的id规格属性
        try {
//            利用模型一对多ORM找出Attribute表的相关的ID集合
            $GoodsType= GoodsType::find($id);
            $goodsAttribute = $GoodsType->goodsAttribute;
            $data = $goodsAttribute->toArray();
            $ids = collect($data)->pluck('id');
//            获取具体的属性参数值和id
            $filteredAttributeValues = GoodsAttributeValue::whereIn('attr_id', $ids)->select(['id','attr_value','attr_id'])->get();
            $attributesValues = $filteredAttributeValues->toArray();
//            组装数组进行返回
            foreach ($data as $key=>$item){
                $data[$key]['attr'] =[];
                foreach($attributesValues as  $attributesValuestem){
                    if($item['id'] == $attributesValuestem['attr_id']){
                        $attributesValueArrayItem = array(
                            'id'=>$attributesValuestem['id'],
                            'attr_value'=>$attributesValuestem['attr_value'],
                            'attr_id'=>$attributesValuestem['attr_id'],
                        );
                      array_push($data[$key]['attr'],$attributesValueArrayItem);
                    }
                }
            }
            logsService::Logs('cx','请求商品规格id为'.$id.'的记录:GoodService->getTypeAttribute',$request->url(),$request->method(),serialize($request->getContent()),200, serialize($data));
        }catch (Exception $exception){
            $data=[];
        }
        return  MsgService::msg(200, $data);
    }
}
