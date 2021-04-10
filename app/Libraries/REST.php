<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

trait REST
{
    public function rmStore($model, $request, $primaryKey, $computed = [])
    {

        $fields = $model::$validator;

        $insert = [];
        foreach ($fields as $key => $value) {
            if (array_key_exists($key, $request)) {
                $insert[$key] = $request[$key];
            }
        }
        foreach ($computed as $key => $value) {
            $insert[$key] = $value;
        }

        $result = $model::create($insert);

        // DB::table('squid_log')->insert($insert);

        return ['status' => 0];
    }
    // store item
    public function Storeorupdate($model, $request, $primaryKey, $computed = [])
    {
        $fields = $model::$validator;
        $validator = Validator::make($request->all(), $fields);
        if ($validator->fails()) {
            return ['status' => -2, 'msg' => $validator->errors()];
        }
        $insert = [];
        foreach ($fields as $key => $value) {
            if ($request->has($key)) {
                $insert[$key] = $request->get($key);
            }
        }
        foreach ($computed as $key => $value) {
            $insert[$key] = $value;
        }
        $pk = ['object_guid'   => $request->get($primaryKey)];
       
        $result =  $model::updateOrCreate($pk, $insert);
        return ['status' => 0, 'id' => $result[$primaryKey]];
    }
    public function rStore($model, $request, $primaryKey, $computed = [])
    {
        $fields = $model::$validator;
        $validator = Validator::make($request->all(), $fields);
        if ($validator->fails()) {
            return ['status' => -2, 'msg' => $validator->errors()];
        }
        $insert = [];
        foreach ($fields as $key => $value) {
            if ($request->has($key)) {
                $insert[$key] = $request->get($key);
            }
        }
        foreach ($computed as $key => $value) {
            $insert[$key] = $value;
        }
        $result = $model::create($insert);
        return ['status' => 0, 'id' => $result[$primaryKey]];
    }
    public function sUpdate($model, $obj, $newValues, $primaryKey, $pre, $computed = [])
    {
        $fields = \ValidationHelper::update($model::$validator, $obj[$primaryKey]);
        $validator = Validator::make($newValues, $fields);

        if ($validator->fails()) {
            return ['status' => -2, 'msg' => $validator->errors()];
        }
        $update = $newValues;
        foreach ($computed as $key => $value) {
            $update[$key] = $value;
        }
        $obj::where($primaryKey ,'=', $pre)
            ->update($update);
        return ['status' => 0, 'id' => $pre];
    }
    public function compsetUpdate($model, $obj, $newValues, $primaryKey, $pre, $computed = [])
    {
        $fields = \ValidationHelper::update($model::$validator, $obj[$primaryKey[0]]);
        $validator = Validator::make($newValues, $fields);

        if ($validator->fails()) {
            return ['status' => -2, 'msg' => $validator->errors()];
        }
        $update = $newValues;
        foreach ($computed as $key => $value) {
            $update[$key] = $value;
        }
        $obj::where($primaryKey[0], $pre)
        ->where($primaryKey[1], $newValues[$primaryKey[1]])
            ->update($update);
        return ['status' => 0, $primaryKey[0] => $pre, $primaryKey[1] => $newValues[$primaryKey[1]]];
    }
    // update item

    public function rUpdate($model, $obj, $newValues, $primaryKey, $computed = [])
    {
        $fields = \ValidationHelper::update($model::$validator, $obj[$primaryKey]);
        $validator = Validator::make($newValues, $fields);
        if ($validator->fails()) {
            return ['status' => -2, 'msg' => $validator->errors()];
        }
        $update = $newValues;
        foreach ($computed as $key => $value) {
            $update[$key] = $value;
        }


        $obj->update($update);
        return ['status' => 0, 'id' => $obj[$primaryKey]];
    }
    // destroy item
    public function rDestroy($obj)
    {
        $obj->delete();
        return ['status' => 0];
    }
    // update multiple items
    public function rMultipleUpdate($model, $request, $primaryKey, $customFillable = false, $fillable = [])
    {
        $ids = $request->get('ids');

        $fields = \ValidationHelper::noRequire($model::$validator);
        $validator = Validator::make($request->all(), $fields);
        if ($validator->fails()) {
            return ['status' => -2, 'msg' => $validator->errors()];
        }

        $newValues = $request->get('request');
        if ($customFillable) {
            $update = [];
            foreach ($newValues as $key => $value) {
                if (in_array($key, $fillable)) {
                    $update[$key] = $value;
                }
            }
        } else {
            $update = $newValues;
        }

        $model
            ::whereIn($primaryKey, $ids)
            ->update($update);

        return ['status' => 0];
    }
    // delete multiple items
    public function rMultipleDelete($model, $request, $primaryKey)
    {
        $ids = $request->get('ids');

        $model::whereIn($primaryKey, $ids)->delete();

        return ['status' => 0];
    }
    // add multiple items
    public function rMultipleAdd($model, $request)
    {
        $items = $request->get('items');

        $model::insert($items);
    }
}
