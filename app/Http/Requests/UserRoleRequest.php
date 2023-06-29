<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRoleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // 根据场景决定是否需要验证category_name
        $role_name = $this->scenes() ?
            'string|max:230' : 'required|string|max:230';
        return [
            'role_name' =>$role_name ,
            'menu_id.*' => 'string|max:20',
            'desc' => 'string|max:20',
        ];
    }
    public function scenes()
    {
        $request = new Request();
        return  $request->method() === 'PUT';
    }
}
