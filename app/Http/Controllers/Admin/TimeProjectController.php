<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimeProjectRequset;
use App\Service\TimeProjectService;
use App\Service\UserRoleService;
use Illuminate\Http\Request;

class TimeProjectController extends Controller
{
    //
    public function timeTrackerAction(TimeProjectRequset $request)
    {
        if($request->method()=== 'PUT')
            return  (new TimeProjectService())->updateUser($request);

        return (new TimeProjectService())->create($request);
    }

    /**获取时间项目
     * @param Request $request
     * @return array
     */
    public function timeTrackerList(Request $request)
    {
        return (new TimeProjectService())->getList($request);
    }

}
