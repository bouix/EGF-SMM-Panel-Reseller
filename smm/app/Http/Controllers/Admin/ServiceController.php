<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Http\Controllers\Admin;

use App\Service;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index');
    }

    public function indexData()
    {
        $services = Service::all();
        return datatables()
            ->of($services)
            ->addColumn('action', 'admin.services.index-buttons')
            ->editColumn('is_subscription_allowed', function ($service) {
                return ($service->is_subscription_allowed == 1) ? 'Yes' : 'No';
            })
            ->toJson();
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        Service::create([
            'name' => $request->input('name'),
            'slug' => str_slug($request->input('name')),
            'description' => $request->input('description'),
            'is_subscription_allowed' => $request->input('is_subscription_allowed'),
            'status' => $request->input('status')
        ]);

        Session::flash('alert', __('messages.created'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/services/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);

        $service = Service::findOrFail($id);
        $service->name = $request->input('name');
        $service->slug = str_slug($request->input('name'));
        $service->description = $request->input('description');
        $service->is_subscription_allowed = $request->input('is_subscription_allowed');
        $service->status = $request->input('status');
        $service->save();

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('admin/services/'.$id.'/edit');

    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        try {
            $service->delete();
        } catch (QueryException $ex) {
            Session::flash('alert', __('messages.service_have_packages'));
            Session::flash('alertClass', 'danger');
            return redirect('/admin/services');
        }

        Session::flash('alert', __('messages.deleted'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/services');
    }
}
