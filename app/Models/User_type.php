<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User_type extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','title'
    ];
    public function User()
    {

        return $this->belongsTo(User::class);

    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_user_type','permission_id','role_id')->using(RolePermission::class);
        //->withPivot('permission_id', 'role_id');
       
    }
    public function scopeActive($query)
    {
        return $query->where('status_id', true);
    }

}
