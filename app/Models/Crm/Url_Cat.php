<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;



class Url_Cat extends Model
{
    protected $fillable = [
        'category_name',
       
        
    ];
    public $timestamps = false;
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->domin . " " . $this->u_name;
    }
    /*
    public function groupsname()
    {
        return $this->hasMany(Grp_user::class, 'user_id');
    }
    public function containername()
    {
        return $this->hasMany(Container_user::class, 'user_id');
    }
    public function ipsofuser()
    {
        return $this->hasMany(Ip_user::class, 'user_id');
    }*/

    public static $validator = [
        'category_name' => 'required|string',
       
    ];
    protected $table = 'url_category';
    protected $primaryKey = 'url_category';
    protected $pk = 'url_category';
}
