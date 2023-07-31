<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TimeTrackRequset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $pathRules = $this->isGetMethod();

        switch ($pathRules){
            case 'POST':
                return $this->createRule();
                break;
            case 'PUT':
                return $this->createRule();
                break;
            default:
                return $this->createRule();
                break;
        }


    }

    /**
     * 后台创建用户规则
     * @return string[]
     */
    public function createRule()
    {
        return [
            'time_project_id' => 'required',
            'start_time'=> 'required|string',
            'end_time'=> 'required|string',
            'title'=> 'required|string',
            'time'=> 'required|string',

        ];
    }

    public function isGetMethod()
    {
        $request = new Request();
        return $request->method();
    }

    /**
     * @return string[]
     * 提示
     */
    public function messages()
    {
        return [
            'time_project_id.required' => '时间项目 ID 是必填项。',
            'start_time.required' => '开始时间是必填项。',
            'start_time.string' => '开始时间必须为字符串。',
            'end_time.required' => '结束时间是必填项。',
            'end_time.string' => '结束时间必须为字符串。',
            'title.required' => '时间备注是必填项。',
            'title.string' => '时间备注必须为字符串。',
            'time.required' => '时间是必填项。',
            'time.string' => '时间必须为字符串。',
        ];
    }
}
