<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ReportTreatment
{

    public static function getAsyncData($model, $request, $columns, $connection, $table, $defaultSortColumn, $defaultOrder)
    {

        $rowsPerPage = $request->get('rowsPerPage');
        $sortBy = $request->get('sortBy');
        $sortDesc = $request->get('sortDesc');
        $search = 'search';
        $searchPhrases = explode(" ", $search);
        $childrens = $request->get('param');
        $deleteMode = $request->get('deleteMode');
        $activeColumnName = $request->get('activeColumnName');
        if (in_array($deleteMode, ['soft', 'both'])) {
            $selectedStatuses = $request->get('selectedStatuses');
            $model->whereIn($activeColumnName, $selectedStatuses);
        }
        if ($search != '') {
            foreach ($childrens['children'] as $children) {

                $searchPhrase =  $children['query']['value'];
                //  $columns = $children['query']['operand'];
                // $mode = $children['query']['operator'];
                $model->where(function ($query) use ($columns, $searchPhrase) {
                    for ($i = 0; $i < count($columns); $i++) {
                        if ($i == 0) {
                            //'cast('.$col .'as varchar)'  $columns[$i]
                            //$query->whereRaw( $columns[$i] , 'like', '%' . $searchPhrase . '%');
                            //$query->whereRaw("cast( " . $columns[$i] . " as varchar) like '%" . $searchPhrase . "%'");
                            $query->whereRaw("cast( " . $columns[$i] . " as varchar) like ? ", ['%' . $searchPhrase . '%']);
                        } else {
                            // $query->orwhereRaw('cast(' . $columns[$i] . ' as varchar)', 'like', '%' . $searchPhrase . '%');
                            // $query->orwhereRaw("cast( " . $columns[$i] . " as varchar) like '%" . $searchPhrase . "%'");
                            $query->orwhereRaw("cast( " . $columns[$i] . " as varchar) like ? ", ['%' . $searchPhrase . '%']);
                        }
                    }
                });
            }
        }


        foreach ($childrens['children'] as $children) {
            if ($children['query']['operand'] != null && in_array($children['query']['operand'], $columns)) {
                $val = $children['query']['value'];
                $col = $children['query']['operand'];
                $mode = $children['query']['operator'];
                $model->where(function ($query) use ($col, $val, $mode) {
                    switch ($mode) {
                            //CAST(25.65 AS varchar); whereRaw('views > ? and votes > ?', [500, 50])
                            //>whereRaw('cast(' . $col . ' as varchar)', 'like', '%' . $val . '%');
                        case '$like':

                            $query->whereRaw("cast( " . $col . " as varchar) like ? ",  ['%' . $val . '%']);
                            break;
                        case '$eq':
                            $query->where($col, '=', $val);
                            break;
                        case 'list':
                            $tmpList = explode(";", $val);
                            $query->whereIn($col, $tmpList);
                            break;
                        default:
                            break;
                    }
                });
            }
        }
        if ($sortBy != null && $sortDesc != null) {
            for ($i = 0; $i < count($sortBy); $i++) {
                $direction = $sortDesc[$i] == 'true' ? 'desc' : 'asc';
                $model->orderBy($sortBy[$i], $direction);
            }
        } else {
            $model->orderBy($defaultSortColumn, $defaultOrder);
        }
        return   $model->paginate($rowsPerPage);
    }
}
