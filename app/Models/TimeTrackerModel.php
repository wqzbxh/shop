<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TimeTrackerModel extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */

    protected $table = 'time_tracker';
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



    public static $queryField = ['time_mark'];

}
