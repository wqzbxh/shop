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
use App\Mail\RegisterMail;
use App\Models\GoodsAttribute;
use App\Models\GoodsAttributeValue;
use App\Models\GoodsType;
use App\Models\menuModel;
use App\Models\TimeProjectModel;
use App\Models\TimeTrackerModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Faker\Core\DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Exception;

class TimeTrackerService
{

    protected $TimeTrackerDB;

    public function __construct()
    {
        $this->TimeTrackerDB = app(TimeTrackerModel::class);
    }

    /**
     * @param $request
     * @return array
     *
     */
    public function createOrUpdate($request)
    {
        //修改
        if($request->method()=== 'PUT'){
            $id = $request->get('id');
            if(empty($id)) return  MsgService::msg(40001, []);
            $this->TimeTrackerDB = TimeTrackerModel::find($id);
            $originValusArr = $this->TimeTrackerDB->toArray();
        }
        $this->TimeTrackerDB->time_mark = $request->get('time_mark');
        $this->TimeTrackerDB->title = $request->get('title');
        $this->TimeTrackerDB->time_project_id = $request->get('time_project_id');
        $this->TimeTrackerDB->start_time = $request->get('start_time');
        $this->TimeTrackerDB->end_time = $request->get('end_time');
        $this->TimeTrackerDB->time = $request->get('time');
        $dateTime = Carbon::parse($request->get('start_time'));
        // 获取周
        $this->TimeTrackerDB->week =$dateTime->week;
        // 获取年
        $this->TimeTrackerDB->year   = $dateTime->year;
        $this->TimeTrackerDB->user_id = AuthMiddleware::$userInfo['user_id'] ;
        //新数据的数据
        $NewDataArray = $this->TimeTrackerDB->getAttributes(); ;
//     保存记录，并捕获可能的异常
        try {
           $this->TimeTrackerDB->save();
        } catch (\Exception $e) {
            return  MsgService::msg(20002, $NewDataArray,$e->getMessage());
        }
        if($request->method()=== 'PUT'){
            ModificationlogsService::ModificationLog(TimeTrackerModel::class,$request->get('id'),$NewDataArray,$originValusArr);
            logsService::Logs('gx','修改了'. $originValusArr['title'].'的时间记录,',$request->url(),$request->method(),serialize($request->getContent()),200, serialize($NewDataArray));
        }else{
            logsService::Logs('cj','创建了'. $NewDataArray['title'].'的时间记录,',$request->url(),$request->method(),serialize($request->getContent()),200, serialize($NewDataArray));
        }

        return  MsgService::msg(200, []);
    }

    /**
     * @param $request
     * @return array
     * 获取产品规格默
     */
    public function getList($request)
    {
        $type = $request->get('type');
//        当没有指定type值得时候以分页形式返回
        switch ($type){
            case 'select':
                // 当为select为下拉款返回全部
                $data = [];
                $selectRow = TimeTrackerModel::where('is_del','=','0')->select('id','name')->get();
                if(!empty($selectRow)){
                    $data = $selectRow->toArray();
                }
                logsService::Logs('cx','请求商品规格下拉框-类型为'. $request->get('type').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
                return  MsgService::msg(200, $data);
            case 'list':
                $data = [];
                $selectRow = TimeTrackerModel::where('is_del','=','0')->where('user_id','=',AuthMiddleware::$userInfo['user_id'])->select('id','time_project_id','time_mark','title','time','start_time as start','end_time as end',)->get();
                if(!empty($selectRow)){
                    $OriginData = $selectRow->toArray();
                    $ids = collect($OriginData)->pluck('time_project_id');
                    $TimeProjectObj = TimeProjectModel::whereIn('id', $ids)->select('id','name','customer_name')->get();
                    $TimeProjectData = $TimeProjectObj->toArray();
                    if( sizeof($TimeProjectData)  < 1 )
                        return  MsgService::msg(40002, []);
//                项目字典
                    $TimeProjectDict = [];
                    foreach ($TimeProjectData as $itemProject)
                    {
                        $TimeProjectDict[$itemProject['id']]=$itemProject['customer_name'].'_'.$itemProject['name'];
                    }
//               title拼接项目名字,前端用@进行打断，获取真正的title值
                    foreach ($OriginData as $key=> $item)
                    {
                        $data[$key]=$item;
                        $data[$key]['title'] = !empty($TimeProjectDict[$item['time_project_id']])  ? $TimeProjectDict[$item['time_project_id']]  .'@'.$item['title']: $item['title'] .'-未知的项目' ;
                    }
                }
                logsService::Logs('cx','请求时间记录-类型为'. $request->get('type').'的记录',$request->url(),$request->method(),serialize($request->getContent()),200, serialize([]));
                return  MsgService::msg(200, $data);
                break;
            case  'project_user':
                return $this->getTimeTrackerByUserId($request);
                break;
                default;
                return  MsgService::msg(200, []);

        }


    }


    /**
     * 删除
     * @param $request
     * @return array
     */
    public function delete($request)
    {
       return  CommonService::delete(TimeTrackerModel::class,$request);
    }

    /**
     * 获取对应的timetracker记录
     * @param $request
     * @return array
     */
    public function getTimeTrackerByUserId($request)
    {
        $user_id = $request->get('user_id');
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $time_project_id = $request->get('time_project_id');
        $data = [];
//        构造查询器
        $query = TimeTrackerModel::where('is_del', '=', '0');
        if (!empty($user_id)) {
            $query->where('user_id', '=', $user_id);
        }else{
            $query->where('user_id', '=', AuthMiddleware::$userInfo['user_id']);
        }
        if (!empty($start_time)) {
            $query->where('start_time', '>=', $start_time);
        }

        if (!empty($end_time)) {
            $query->where('end_time', '<=', $end_time);
        }

        if (!empty($time_project_id)) {
            $query->where('time_project_id', '=', $time_project_id);
        }

        $selectRow = $query->select('id','title','start_time','end_time','time',)->get();
        if(!empty($selectRow)){
            $data['rows']  = $selectRow->toArray();
            // 定义一个变量用于累加时间
            $totalTimeInSeconds = 0;
            // 将时间字符串解析为 DateTime 对象，并累加到 totalTimeInSeconds 变量中
            foreach ($data['rows'] as $item) {
                $timeInSeconds = strtotime($item['time']) - strtotime('today');
                $totalTimeInSeconds += $timeInSeconds;
            }
            // 将总时间的秒数转换回时间字符串格式
            $data['total_time'] = gmdate("H:i:s", $totalTimeInSeconds);
        }
        return  MsgService::msg(200, $data);


    }
}
