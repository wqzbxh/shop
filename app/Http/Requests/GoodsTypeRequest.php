<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GoodsTypeRequest extends FormRequest
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
            'name' => 'required|string|unique:goods_type|max:60',
            'sort' => 'required',
        ];
    }

    public function isGetPath()
    {
        $request = new Request();
        return $request->method();
    }
}
