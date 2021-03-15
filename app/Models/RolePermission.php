<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission  extends Pivot
{
    use HasFactory;

    protected $table = 'permission_user_type';
    protected $fillable = [
        'id', 'role_id', 'permission_id'
    ];
    // public function User_type()
    // {
    //     return $this->hasOne(User_type::class,"id","role_id");
    // }
    public function Permission()
    {
        return $this->hasOne(Permission::class,"id","permission_id")->select('id','title');
    }
    public function User()
    {

        return $this->belongsTo(User::class);

    }
}
