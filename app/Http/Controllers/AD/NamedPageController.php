<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\NamedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamedPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = NamedPage::class;
    private $pk = 'block_page_id';

    public function index()
    {
        return NamedPage
            ::orderBy('title', 'asc')
            ->get();
    }

    public function show($id)
    {
        $NamedPageInfo = NamedPage
            ::where('block_page_id', $id)
                ->first();
        if ($NamedPageInfo == null) {
            return [];
        }

        return $NamedPageInfo;
    }
    public function update($prefix, Request $request, NamedPage $model)
    {
        $page = NamedPage::findOrFail($prefix);
        $pageName = $page->title;

        if ($page->default_page_flag == true) {
            if ($request->get('default_page_flag') === false || $request->get('default_page_flag') === "false" || $request->get('default_page_flag') === "0") {
                return ['status' => -2,
                    "msg" => [
                        "default_page_flag" => [
                            "Can't set $pageName's flag to false, please assign another default page to true to enable the update"
                        ]
                    ]
                ];
            }
        }
        
        if ($request->get('default_page_flag') === true || $request->get('default_page_flag') === "true" || $request->get('default_page_flag') === "1") {
            DB::connection('pgsql3')->table('block_page')->update(['default_page_flag' => false]);
        }

        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);

    }
    public function store(Request $request)
    {
        if ($this->m::count() === 0) {
            $request->request->set('default_page_flag', 'true');
        }

        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, NamedPage $model)
    {
        $page = NamedPage::findOrFail($prefix);
        
        if ($page->default_page_flag == true) {
            return ['status' => -2,
                "msg" => [
                    "default_page_flag" => [
                        'Can not delete block page with true flag on it'
                    ]
                ]
            ];
        }

        $model->destroy($prefix);

        return ['status' => 0];
    }
    public function search(Request $request)
    {
        $columns = [
            'block_page_id',
            'title',
            'html_content',
            'default_page_flag'
        ];
        try {  
        
            $model = NamedPage
                ::select($columns);
       
            return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'block_page', 'title', 'ASC');
        }catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function itemsList($mode, $search = '')
    {
        $NamedPage = DB::table('block_page')
            ->select('block_page_id', DB::raw("title as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'block_page_id') {
            $NamedPage = $NamedPage->where('block_page_id', $search);
        } else if ($mode == 'phrase') {
            $NamedPage = $NamedPage->where(DB::raw("title"), 'like', '%' . $search . '%');
        }
        return $NamedPage->take(100)->get();
    }
}
