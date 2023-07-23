<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeProjectModel extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'time_project';
    /**
     * 与表关联的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;


    public static $queryField = ['customer_name','info','project_no','name'];
    /**
     * 获得属性
     */
//    public function goodsAttribute()
//    {
//        return $this->hasMany(GoodsAttribute::class,'cid','id')->select(['id','attr_name']);
//    }
}
