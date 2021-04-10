<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Url_Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UrlcatController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Url_Cat::class;
    private $pk = 'url_cat';

    public function index()
    {
        return Url_Cat
            ::orderBy('category_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $User_nameInfo = Url_Cat
            ::where('url_cat', $id)
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['url_cat', 'category_name'];

        $model = Url_Cat
            ::orderBy('category_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'url_category', 'category_name', 'ASC');
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function itemsList($mode, $search = '')
    {
        $url_category = DB::table('url_category')
            ->select('url_cat', DB::raw("CONCAT(category_name) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'url_cat') {
            $url_category = $url_category->where('url_cat', $search);
        } else if ($mode == 'phrase') {
            $url_category = $url_category->where(DB::raw("CONCAT(category_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $url_category->take(100)->get();
    }
}
