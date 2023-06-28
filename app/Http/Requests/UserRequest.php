<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
        // 根据场景决定是否需要验证 name 字段
        $nameRules = $this->isRegisterRequest() ? 'required|string|max:60' : '';
        $emailRules = $this->isRegisterRequest() ? 'required|email|unique:admin_user': 'required|email';

        return [
            'name' => $nameRules,
            'email' => $emailRules,
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * 判断是否为注册请求
     *
     * @return bool
     */
    public function isRegisterRequest()
    {
        return $this->path() === 'api/register';
    }
}
