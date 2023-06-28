<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModificationlogsModel extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'modification_logs';
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
}
