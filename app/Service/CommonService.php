<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:55
 */
namespace App\Service;
use App\Http\Resources\UserResource;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;
class CommonService {

    /**
     * @param $modelClass
     * @param $where
     * @return false|mixed
     */
    public static function getOne($modelClass, $where) {
        // 实例化
        $model = new $modelClass;
        // 获取原始数据
        $originValues = $model->where($where)->first();
        if (!$originValues) {
            return false;
        }
        $originValuesArr = $originValues->toArray();
        return $originValuesArr;
    }
    /**
     * 分页获取数据
     * @param $modelClass
     * @param $request
     * @return array
     */
    public  static  function getList($modelClass,$request) {
        $returnData['count'] =  0;
        $returnData['resource'] =  [];
        //引用模型
        $resourceModel = app($modelClass);
        //获取分页数据参数
        $start = $request->get('start');
        $size = $request->get('size');
        $filters = $request->get('filters');
        $globalFilter  = $request->get('globalFilter');
        $sorting = $request->get('sorting');
        //过滤条件转为数组
        $filtersArr = json_decode($filters,true);
        //排序条件格式化
        $sortingArr = json_decode($sorting,true);
        //设置了具体filters字段则$globalFilter失效
        $where = [];
        if(count($filtersArr) > 0 ) {
            foreach ($filtersArr as  $value) {
                $where[] = [$value['id'], 'like', '%'.$value['value'].'%'];
            }
        }
        try {
            //构造查询器
            $query = $resourceModel::where($where);
            //全局查找，仅当$filtersArr为空时生效，否则以为主$filtersArr
            if(count($filtersArr) == false && !empty($globalFilter)) {
                foreach ($resourceModel::$queryField as $condition) {
                    $query->orWhere(function ($query) use ($condition,$globalFilter) {
                        $query->where($condition, 'LIKE', '%' . $globalFilter . '%');
                    }
                    );
                }
            }
            $query->where('is_del', '=', '0');
            $count  = $query->count();
            //排序

            if(count($sortingArr) > 0) {
                $orderBy = $sortingArr[0];
                $rule = $orderBy['desc'] ? 'desc' : 'asc';
                $resource  =   $query->offset($start)->orderBy($orderBy['id'], $rule)->limit($size)->get();
            } else {
                $resource  =  $query->offset($start)->limit($size)->get();
            }
            $returnData['count'] =  $count;
            $returnData['resource'] =  $resource;
        }
        catch (Exception $e) {
            $returnData['count'] =  0;
            $returnData['resource'] =  [];
        }
        return  $returnData;
    }
//   分别
    public static function selectList($modelClass){

        $returnData['resource'] =  [];
        $resourceModel = app($modelClass);
        $resourceModel->where('is_del', '=', '0');
        $resource = $resourceModel::select($modelClass::$selectField)->get();
        $returnData['resource'] =  $resource;
        return  $returnData;

    }
}
