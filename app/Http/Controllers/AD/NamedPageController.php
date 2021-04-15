<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\NamedPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NamedPageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store']]);
        // $this->middleware('role:update', ['only' => ['update']]);
        // $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = NamedPage::class;
    private $pk = 'block_page_id';

    public function index()
    {
        return NamedPage::orderBy('title', 'asc')->get();
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
    public function update(Request $request, NamedPage $page)
    {
        $request->validate([
            'title' => 'required',
        ]);
        try{
            $pageName = $page->title;

            if ($page->default_page_flag == true) {
                if ($request->get('default_page') === false || $request->get('default_page') === "false" || $request->get('default_page') === "0") {
                    return response()->json(['status'=>'error','message'=>"Can't set $pageName's flag to false, please assign another default page to true to enable the update"]);
                }
            }
            
            if ($request->get('default_page') === true || $request->get('default_page') === "true" || $request->get('default_page') === "1") {
                DB::connection('pgsql')->table('block_page')->update(['default_page_flag' => false]);
            }
            $page->title = $request->title;
            $page->default_page_flag = $request->default_page; 
            $page->update();
            // return $this->sUpdate($this->m, $page, $request->all(), $this->pk, $page->block_page_id);

            return response()->json(['status'=>'success','message'=>'Page Updated Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'title' => 'required',
        ]);
        try{
            if ($this->m::count() === 0) {
                $request->request->set('default_page_flag', 'true');
            }
            $this->rStore($this->m, $request, $this->pk);

            return response()->json(['status'=>'success','message'=>'Page Added Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }

    public function destroy(NamedPage $page)
    {
        try{
            if ($page->default_page_flag == true) {
                return response()->json(['status'=>'error','message'=>'Can not delete block page with true flag on it !']);
            }
            $page->delete();

            return response()->json(['status'=>'success','message'=>'Page Deleted Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }

    public function edit(NamedPage $page) {
        return $page;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function block_pages()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/block-pages/index', ['pageConfigs' => $pageConfigs]);
    }

    // Block pages List
    public function block_pages_list(Request $request)
    {
        $title              = $request->title;
        $default_page_flag  = $request->default_page;
        $block_pages        = NamedPage::orderBy('when_created','desc');
        if($title != ''){
            $block_pages->where('title','LIKE','%'.$title.'%');
        }
        if($default_page_flag != ''){
            $block_pages->where('default_page_flag','LIKE','%'.$default_page_flag.'%');
        }
        $block_pages = $block_pages->paginate(10);
        return $block_pages;
    }
}
