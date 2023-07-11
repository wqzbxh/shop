<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'goods_type';
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


    public static $queryField = ['name','create_at'];
    /**
     * 获得属性
     */
    public function goodsAttribute()
    {
        return $this->hasMany(GoodsAttribute::class);
    }
}
