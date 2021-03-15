<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permission';
    protected $fillable = [
        'id', 'title', 'status_id'
    ];

    // public function roles()
    // {
    //     return $this->belongsTo(RolePermission::class,"permission_id","id");

    // }

    public function roles()
    {
        return $this->belongsToMany(User_type::class);
    }
    // public function roles()
    // {
    //     return $this->belongsToMany(Permission::class,'permission_user_type','permission','id')->using(RolePermission::class);
    // }
}
