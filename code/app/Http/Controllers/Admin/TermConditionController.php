<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\TermCondition;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class TermConditionController extends Controller
{
    public function __construct()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.term-condition.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.term-condition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'name' => 'required'
        ]);

        $request['slug'] = str_slug($request->name,'-');

        $requestData = $request->all();

        TermCondition::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/term-and-condition');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $TermCondition = TermCondition::findOrFail($id);

        return view('admin.term-condition.show', compact('TermCondition'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $TermCondition = TermCondition::findOrFail($id);

        return view('admin.term-condition.edit', compact('TermCondition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'name' => 'required'
        ]);

        $request['slug'] = str_slug($request->name,'-');

        $requestData = $request->all();

        $TermCondition = TermCondition::findOrFail($id);
        $TermCondition->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/term-and-condition');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        if(Auth::user()->role == User::ROLE_SUPERADMIN) {
            TermCondition::destroy($id);
            Alert::success('Your data already deleted !', 'Success !');

            return redirect('admin/term-and-condition');
        }
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $TermCondition = TermCondition::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'code', 'name', 'slug', 'content'])->orderby('id', 'ASC')->get();

        return Datatables::of($TermCondition)

            ->addColumn('action', function ($TermCondition) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<p><a href="term-and-condition/' . $TermCondition->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a></p>
                        <p><a onclick="deleteData(' . $TermCondition->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a></p>';
                }else{
                    return '<p><a href="term-and-condition/' . $TermCondition->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a></p>';
                }
            })
            ->addColumn('content', function ($TermCondition) {
                return html_entity_decode($TermCondition->content);
            })
            ->rawColumns(['action', 'content'])
            ->make(true);
    }
}
