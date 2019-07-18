<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\QuoteTemplate;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class QuoteTemplateController extends Controller
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
        return view('admin.quote-template.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.quote-template.create');
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
            'header' => 'required',
            'footer' => 'required',
        ]);
        $requestData = $request->all();
        QuoteTemplate::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/quote-template');
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
        $QuoteTemplate = QuoteTemplate::findOrFail($id);

        return view('admin.quote-template.show', compact('QuoteTemplate'));
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
        $QuoteTemplate = QuoteTemplate::findOrFail($id);

        return view('admin.quote-template.edit', compact('QuoteTemplate'));
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
            'header' => 'required',
            'footer' => 'required',
        ]);

        $requestData = $request->all();

        $QuoteTemplate = QuoteTemplate::findOrFail($id);
        $QuoteTemplate->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/quote-template');
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
            QuoteTemplate::destroy($id);

            Alert::success('Your data already deleted !', 'Success !');

            return redirect('admin/quote-template');
        }
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $QuoteTemplate = QuoteTemplate::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'code', 'header', 'footer'])->orderby('code', 'ASC')->get();

        return Datatables::of($QuoteTemplate)

            ->addColumn('action', function ($QuoteTemplate) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<p><a href="quote-template/' . $QuoteTemplate->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a></p>
                        <p><a onclick="deleteData(' . $QuoteTemplate->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a></p>';
                }else{
                    return '<p><a href="quote-template/' . $QuoteTemplate->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a></p>';
                }
            })
            ->addColumn('header', function ($QuoteTemplate) {
                return html_entity_decode($QuoteTemplate->header);
            })
            ->addColumn('footer', function ($QuoteTemplate) {
                return html_entity_decode($QuoteTemplate->footer);
            })
            ->rawColumns(['action', 'header', 'footer'])
            ->make(true);
    }
}
