<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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

        $pathRules = $this->isGetPath();

        switch ($pathRules){
            case 'api/register':
                return $this->registerReques();
                break;
            case 'api/user':
                return $this->userReques();
                break;
            default:
                return $this->defaultReques();
                break;
        }


    }

    /**
     *默认匹配
     * @return string[]
     */

    public function defaultReques()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }
    /**
     * 注册匹配
     * @return string[]
     */

    public function registerReques()
    {
        return [
            'name' => 'required|email|unique:admin_user',
            'email' => 'required|string|max:60',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * 后台创建用户规则
     * @return string[]
     */
    public function userReques()
    {
        $request = new Request();
        var_dump($request->method());
        $pd =  $request->method() === 'PUT' ?  'string|min:6': 'required|string|min:6';
        $email =  $request->method() === 'PUT' ?  'required|email':  'required|email|unique:admin_user';
        return [
            'email' => $email,
            'name' => 'required|string|max:60',
            'phone' => 'required|string|unique:admin_user',
            'password' => $pd,
        ];
    }

    public function isGetPath()
    {
        return $this->path();
    }
}
