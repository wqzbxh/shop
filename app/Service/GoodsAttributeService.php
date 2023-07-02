<?php
/**
 * Created by : PhpStorm
 * User: 哑巴湖大水怪（王海洋）
 * Date: 2023/6/3
 * Time: 17:22
 */

namespace App\Service;

use App\Mail\RegisterMail;
use App\Models\GoodsAttribute;
use App\Models\menuModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GoodsAttributeService
{

    protected $goodsAttributeDB;

    public function __construct()
    {
        $this->goodsAttributeDB = app(GoodsAttribute::class);
    }
    public  function create($data,$cid)
    {
      if(!empty($data)){
          $goodsAttributeValue = [];
          foreach ($data as $value)
          {
              $goodsAttribute = new GoodsAttribute(); // 创建新的模型实例
              $goodsAttribute->attr_name = $value['name'];
              $goodsAttribute->sort = $value['sort'];
              $goodsAttribute->cid =$cid;
              $goodsAttribute->attr_val = implode(',',$value['attribute']);
              $goodsAttribute->save();
              $newId =$goodsAttribute->id;  // 获取新添加的记录的 ID
              logsService::Logs('cj','创建了规格参数为'.$value['name'].'的记录','/','POST',serialize($value),200, serialize([]));
              foreach ($value['attribute'] as $attributeItem){
                  $attributeItemArray = [];
                  $attributeItemArray['attr_id'] = $newId;
                  $attributeItemArray['attr_value'] = $attributeItem;
                  array_push($goodsAttributeValue,$attributeItemArray);
              }
          }
          if(!empty($goodsAttributeValue))
          {
              (new GoodsAttributeValueService())->create($goodsAttributeValue);
          }
          return true;
      }
        return false;
    }
}
