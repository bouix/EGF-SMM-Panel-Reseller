<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\API;
use App\Service;
use App\Package;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PackageController extends Controller
{

    public function index()
    {
        return view('admin.packages.index');
    }

    public function indexData()
    {

        $packages = Package::with('service');
        return datatables()
            ->of($packages)
            ->addColumn('action', 'admin.packages.index-buttons')
            ->editColumn('description', '{{ str_limit($description,50) }}')
            ->editColumn('price_per_item', '{{ getOption(\'currency_symbol\') . number_format(($price_per_item * getOption(\'display_price_per\')),2, getOption(\'currency_separator\'), \'\') }}')
            ->toJson();

    }


    public function create()
    {
        $apis = API::all();
        $services = Service::where(['status' => 'ACTIVE'])->get();
        return view('admin.packages.create', compact('services', 'apis'));
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'service_id' => 'required',
            'name' => 'required',
            'price_per_item' => 'required|numeric',
            'minimum_quantity' => 'required|numeric',
            'maximum_quantity' => 'required|numeric',
            'description' => 'required',
        ]);

        // Calculate the minimum quantity is not too much lower so
        // The order price will become < 0

        $price_per_item = $request->input('price_per_item');
        $minimum_quantity = $request->input('minimum_quantity');

        // Regular user minimum order Price to .00 decimal points
        $min_regular_price = (float)$price_per_item * $minimum_quantity;
        $min_regular_price = number_format($min_regular_price, 2, '.', '');

        if ($min_regular_price <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['minimum_quantity' => 'Please increase the quantity, so minimum order price would be 0.01']);
        }

        $preferred_api_id = !empty($request->input('preferred_api_id')) ? $request->input('preferred_api_id') : null;

        Package::create([
            'service_id' => $request->input('service_id'),
            'name' => $request->input('name'),
            'slug' => str_slug($request->input('name')),
            'price_per_item' => $request->input('price_per_item'),
            'minimum_quantity' => $request->input('minimum_quantity'),
            'maximum_quantity' => $request->input('maximum_quantity'),
            'status' => $request->input('status'),
            'preferred_api_id' => $preferred_api_id,
            'custom_comments' => $request->input('custom_comments'),
            'description' => $request->input('description')
        ]);
        Session::flash('alert', __('messages.created'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/packages/create');
    }


    public function show($id)
    {
        return redirect('/admin/packages');
    }


    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $apis = API::all();
        $services = Service::where(['status' => 'ACTIVE'])->get();
        return view('admin.packages.edit', compact('services', 'package', 'apis'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'service_id' => 'required',
            'name' => 'required',
            'price_per_item' => 'required|numeric',
            'minimum_quantity' => 'required|numeric',
            'maximum_quantity' => 'required|numeric',
            'description' => 'required',
        ]);

        // Calculate the minimum quantity is not too much lower so
        // The order price will become < 0

        $price_per_item = $request->input('price_per_item');
        $minimum_quantity = $request->input('minimum_quantity');

        // Regular user minimum order Price to .00 decimal points
        $min_regular_price = (float)$price_per_item * $minimum_quantity;
        $min_regular_price = number_format($min_regular_price, 2, '.', '');

        if ($min_regular_price <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['minimum_quantity' => 'Please increase the quantity, so minimum order price would be 0.01']);
        }

        $package = Package::findOrFail($id);

        $preferred_api_id = !empty($request->input('preferred_api_id')) ? $request->input('preferred_api_id') : null;
        $package->service_id = $request->input('service_id');
        $package->name = $request->input('name');
        $package->slug = str_slug($request->input('name'));
        $package->price_per_item = $request->input('price_per_item');
        $package->minimum_quantity = $request->input('minimum_quantity');
        $package->maximum_quantity = $request->input('maximum_quantity');
        $package->status = $request->input('status');
        $package->description = $request->input('description');
        $package->preferred_api_id = $preferred_api_id;
        $package->custom_comments = $request->input('custom_comments');
        $package->save();

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('admin/packages/'.$id.'/edit');
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        try {
            $package->delete();
        } catch (QueryException $ex) {
            Session::flash('alert', __('messages.package_have_orders'));
            Session::flash('alertClass', 'danger');
            return redirect('/admin/packages');
        }

        Session::flash('alert', __('messages.deleted'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/packages');
    }
}
