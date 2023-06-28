<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GoodsCategoryRequset extends FormRequest
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
        // 根据场景决定是否需要验证category_name
        $category_name = $this->scenes() ?
            'string|max:230' : 'required|string|max:230';
        return [
            'category_name' =>$category_name ,
            'order' => 'numeric|max:20',
            'type' => 'string|max:20',
        ];
    }
    public function scenes()
    {
        $request = new Request();
        return  $request->method() === 'PUT';
    }

}
