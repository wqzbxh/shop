<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'admin_user';
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

    public static $queryField = ['name','email','phone','realname','idcard'];

    public function role()
    {
        return $this->hasOne(UserRole::class,'id','role_id');
    }


    public function create($data)
    {
        $this->name = $data['name'];
        $this->password = $data['password'];
        $this->salt = $data['salt'];
        $this->email = $data['email'];
        $result =$this->save();
        return $result;
    }



}
