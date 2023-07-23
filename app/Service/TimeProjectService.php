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
use App\Models\TimeProjectModel;
use App\Models\UserModel;
use App\Utils\Tools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Exception;

class TimeProjectService
{

    protected $timeProjectDB;

    public function __construct()
    {
        $this->timeProjectDB = app(TimeProjectModel::class);
    }
    public function create($request)
    {
        $this->timeProjectDB->project_no = $request->get('project_no') ?? Tools::randString(10,'alphanumeric');
        $this->timeProjectDB->cost = $request->get('cost') ?? '0.00' ;
        $this->timeProjectDB->budget = $request->get('budget') ?? '0.00';
        $this->timeProjectDB->name = $request->get('name');
        $this->timeProjectDB->info = $request->get('info');
        $this->timeProjectDB->time_estimate = $request->get('time_estimate');
        $this->timeProjectDB->customer_name = $request->get('customer_name');
        $this->timeProjectDB->start_date = $request->get('start_date');
//        保存之前的数据
        $oldData = $this->timeProjectDB->getAttributes(); ;
//     保存记录，并捕获可能的异常
        try {
            $result = $this->timeProjectDB->save();
        } catch (\Exception $e) {
            return  MsgService::msg(20002, $oldData);
        }

        logsService::Logs('cj','创建了'. $request->get('name').'的记录,',$request->url(),$request->method(),serialize($request->getContent()),200, serialize($oldData));
        return  MsgService::msg(200, []);
    }

    /**
     * @param $request
     * @return array
     * 获取产品规格默
     */
    public function getList($request)
    {
        $type =  $request->get('type') ?? false;
//        当没有指定type值得时候以分页形式返回
        if($type == 'select'){
//            当为select为下拉款返回全部
            $data = [];
            $selectRow = TimeProjectModel::where('is_del','=','0')->select('id','name')->get();
            if(!empty($selectRow)){
                $data = $selectRow->toArray();
            }
            logsService::Logs('cx','请求时间项目下拉框-类型为'. $request->get('type').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
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
