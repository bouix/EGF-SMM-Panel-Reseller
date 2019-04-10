<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use Session;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit($slug)
    {
        $page = Page::where(['slug' => $slug])->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'content' => 'required',
            'meta_tags' => 'required'
        ]);

        $page = Page::findOrFail($id);
        $page->content = $request->input('content');
        $page->meta_tags = $request->input('meta_tags');
        $page->save();

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');

        return redirect('/admin/page-edit/'.$page->slug);
    }
}
