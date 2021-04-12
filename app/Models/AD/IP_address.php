<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\ObjectinContainer;

class IP_address extends Model
{
    
 
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'ip_address_points',
        'ip_address_ranges',
        'name',
        'description',
        'is_enabled',
     

    ];
   
    public function ipincontainers()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Container");
    } 

    public static $validator = [
        'name' => 'string',
        'description' => 'string',
        'is_enabled' =>'string',
        'ip_address_points' => 'string',
      
    ];
    protected $appends = ['points', 'ip_address_from', 'ip_address_to'];
          
    public function getIpAddressFromAttribute()
    {
        if ($this->ip_address_ranges !== null) {
            $pars =  $this->pg_array_parse($this->ip_address_ranges);
            $result = $this->pg_array_parse2($pars[0]);

            return  $result[0];

        }

        return null;
    }
    
    public function getIpAddressToAttribute()
    {
        if ($this->ip_address_ranges !== null
        ) {
            $pars =  $this->pg_array_parse($this->ip_address_ranges);
            $result = $this->pg_array_parse2($pars[0]);

            return  $result[1];
        }

        return null;
    }
    
    public function getPointsAttribute()
    {
        if ($this->ip_address_points !== null) {
            $pars =  $this->pg_array_parse($this->ip_address_points);
           // $result = $this->pg_array_parse2($pars[0]);

            return  $pars;
        }

        return null;
    }


public function pg_array_parse($s, $start = 0, &$end = null)
{
    if (empty($s) || $s[0] != '{') return null;
    $return = array();
    $string = false;
    $quote='';
    $len = strlen($s);
    $v = '';
    for ($i = $start + 1; $i < $len; $i++) {
        $ch = $s[$i];

        if (!$string && $ch == '}') {
            if ($v !== '' || !empty($return)) {
                $return[] = $v;
            }
            $end = $i;
            break;
        } elseif (!$string && $ch == '{') {
            $v =$this->pg_array_parse($s, $i, $i);
        } elseif (!$string && $ch == ','){
            $return[] = $v;
            $v = '';
        } elseif (!$string && ($ch == '"' || $ch == "'")) {
            $string = true;
            $quote = $ch;
        } elseif ($string && $ch == $quote && $s[$i - 1] == "\\") {
            $v = substr($v, 0, -1) . $ch;
        } elseif ($string && $ch == $quote && $s[$i - 1] != "\\") {
            $string = false;
        } else {
            $v .= $ch;
        }
    }

    return $return;
}
    public function pg_array_parse2($s, $start = 0, &$end = null)
    {
        if (empty($s) || $s[0] != '(') return null;
        $return = array();
        $string = false;
        $quote = '';
        $len = strlen($s);
        $v = '';
        for ($i = $start + 1; $i < $len; $i++) {
            $ch = $s[$i];

            if (!$string && $ch == ')') {
                if ($v !== '' || !empty($return)) {
                    $return[] = $v;
                }
                $end = $i;
                break;
            } elseif (!$string && $ch == '(') {
                $v = $this->pg_array_parse2($s, $i, $i);
            } elseif (!$string && $ch == ',') {
                $return[] = $v;
                $v = '';
            } elseif (!$string && ($ch == '"' || $ch == "'")) {
                $string = true;
                $quote = $ch;
            } elseif ($string && $ch == $quote && $s[$i - 1] == "\\") {
                $v = substr($v, 0, -1) . $ch;
            } elseif ($string && $ch == $quote && $s[$i - 1] != "\\") {
                $string = false;
            } else {
                $v .= $ch;
            }
        }

        return $return;
    } 
    
    
    

    protected $table = 'ip_address';
    protected $primaryKey = 'ts_id';
    protected $pk = 'ts_id';
    

}
