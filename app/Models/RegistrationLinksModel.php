<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationLinksModel extends Model
{
    use HasFactory;
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'registration_links';
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


    public function create($email,$token)
    {
        $this->expiration_time = time()+86400;
        $this->token = $token;
        $this->email = $email;
        $result =$this->save();
        return $result;
    }

}
