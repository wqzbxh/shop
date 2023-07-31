<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TimeProjectRequset extends FormRequest
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
                return $this->updateRule();
                break;
            default:
                return $this->updateRule();
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
            'name' => 'required|string|unique:time_project|max:60',
            'customer_name'=> 'required|string',
            'info'=> 'required|string',
            'time_estimate'=> 'required|string',
        ];
    }

    /**
     * @return string[]修改规则
     */
    public function updateRule()
    {
        return [
            'customer_name'=> 'required|string',
            'info'=> 'required|string',
            'time_estimate'=> 'required|string',
        ];
    }

    public function isGetMethod()
    {
        $request = new Request();
        return $request->method();
    }

    public function messages()
    {
        return [
            'name.required' => '项目字段是必填的。',
            'name.string' => '项目字段必须是字符串。',
            'name.unique' => '项目字段已存在。',
            'name.max' => '项目字段不能超过60个字符。',
            'customer_name.required' => '客户名称字段是必填的。',
            'customer_name.string' => '客户名称字段必须是字符串。',
            'info.required' => '信息字段是必填的。',
            'info.string' => '信息字段必须是字符串。',
            'time_estimate.required' => '时间估计字段是必填的。',
            'time_estimate.string' => '时间估计字段必须是字符串。',
        ];
    }
}
