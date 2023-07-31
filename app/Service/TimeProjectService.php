<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Resources\GoodsTypeResource;
use App\Http\Resources\TimeProjectResource;
use App\Mail\RegisterMail;
use App\Models\GoodsAttribute;
use App\Models\GoodsAttributeValue;
use App\Models\GoodsType;
use App\Models\menuModel;
use App\Models\TimeProjectModel;
use App\Models\UserModel;
use App\Utils\Tools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Exception;

class TimeProjectService
{

    protected $timeProjectDB;

    public function __construct()
    {
        $this->timeProjectDB = app(TimeProjectModel::class);
    }
    public function createOrUpdate($request)
    {
        //修改
        if($request->method()=== 'PUT'){
            $id = $request->get('id');
            if(empty($id)) return  MsgService::msg(40001, []);
            $this->timeProjectDB = TimeProjectModel::find($id);
            $originValusArr = $this->timeProjectDB->toArray();
        }
        $this->timeProjectDB->project_no = $request->get('project_no') ?? Tools::randString(10,'alphanumeric');
        $this->timeProjectDB->cost = $request->get('cost') ?? '0.00' ;
        $this->timeProjectDB->budget = $request->get('budget') ?? '0.00';
        $this->timeProjectDB->name = $request->get('name');
        $this->timeProjectDB->info = $request->get('info');
        $this->timeProjectDB->time_estimate = $request->get('time_estimate');
        $this->timeProjectDB->customer_name = $request->get('customer_name');
        $this->timeProjectDB->start_date = $request->get('start_date');
//        保存之前的数据
        $NewDataArray = $this->timeProjectDB->getAttributes(); ;
//     保存记录，并捕获可能的异常
        try {
            $this->timeProjectDB->save();
        } catch (\Exception $e) {
            return  MsgService::msg(20002, $NewDataArray,$e->getMessage());
        }
        if($request->method()=== 'PUT'){
            ModificationlogsService::ModificationLog(TimeProjectModel::class,$request->get('id'),$NewDataArray,$originValusArr);
            logsService::Logs('gx','修改了ID：'. $id.'的项目记录,',$request->url(),$request->method(),serialize($request->getContent()),200, serialize($NewDataArray));
        }else{
            logsService::Logs('cj','创建了'. $request->get('name').'的记录,',$request->url(),$request->method(),serialize($request->getContent()),200, serialize($NewDataArray));
        }

        return  MsgService::msg(200, []);
    }

    /**
     * @param $request
     * @return array
     * 获取项目
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
        $resource = CommonService::getList(TimeProjectModel::class,$request,[['user_id', '=', AuthMiddleware::$userInfo['user_id']]]);
        $data['data'] =TimeProjectResource::collection($resource['resource']);
        $data['total'] = $resource['count'];
        Log::info('This is an info log message.');
        Log::debug('Request handled by Swoole.');
        logsService::Logs('cx','请求时间项目分页数据的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
        return  MsgService::msg(200, $data);
    }



    /**
     * @param $request
     * @return array
     */
    public function delete($request)
    {
        return  CommonService::delete(TimeProjectModel::class,$request);
    }
}
