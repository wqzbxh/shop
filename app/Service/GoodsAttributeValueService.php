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
use App\Models\GoodsAttributeValue;
use App\Models\menuModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GoodsAttributeValueService
{

    protected $goodsAttributeDB;

    public function __construct()
    {
        $this->goodsAttributeValueDB = app(GoodsAttributeValue::class);
    }
    public  function create($data)
    {
      if(!empty($data)){
          $this->goodsAttributeValueDB->insert($data);
          logsService::Logs('cj','批量添加了规格参数属性的记录','/','POST',serialize($data),200, serialize([]));
          return true;
      }
    }
}
