<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Http\Requests\TimeTrackRequset;
use App\Service\TimeTrackerService;
use Illuminate\Http\Request;
class TimeTrackController extends Controller
{
    //
    public function timeTrackerAction(TimeTrackRequset $request)
    {
//        if($request->method()=== 'PUT')
//            return  (new TimeTrackerService())->update($request);

        return (new TimeTrackerService())->createOrUpdate($request);
    }
    public function timeTrackerList(Request $request)
    {
        return (new TimeTrackerService())->getList($request);
    }

    /**
     * 删除操作
     * @param Request $request
     * @return array
     */
    public function timeTrackerDelete(Request $request)
    {
        return  (new TimeTrackerService())->delete($request);
    }
}
