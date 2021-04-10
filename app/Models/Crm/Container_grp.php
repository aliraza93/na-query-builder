<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Container;


class Container_grp extends Model
{
    protected $fillable = ['group_id', 'container_id'];

    public static $validator = [
        'group_id' => 'required|string',
        'container_id' => 'required|string',
    ];

    public $timestamps = false;

    public function containername()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
    protected $table = 'container_group';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
