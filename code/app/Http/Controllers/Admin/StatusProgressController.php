<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\StatusProgress;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;

class StatusProgressController extends Controller
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
        return view('admin.status-progress.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.status-progress.create');
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
            'name_progress' => 'required'
        ]);

        $requestData = $request->all();

        StatusProgress::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/status-progress');
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
        $status = StatusProgress::findOrFail($id);

        return view('admin.status-progress.show', compact('status'));
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
        $status = StatusProgress::findOrFail($id);

        return view('admin.status-progress.edit', compact('status'));
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
            'name_progress' => 'required'
        ]);

        $requestData = $request->all();

        $status = StatusProgress::findOrFail($id);
        $status->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/status-progress');
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
        StatusProgress::destroy($id);

        Alert::success('Your data already deleted !', 'Success !');

        return redirect('admin/status-progress');
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $status = StatusProgress::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'name_progress'])->orderby('id', 'ASC')->get();

        return Datatables::of($status)

            ->addColumn('action', function ($status) {
                return '<a href="status-progress/'.$status->id.'/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>';
                        // <a onclick="deleteData('.$status->id.')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
