<?php namespace App\Http\Controllers;
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
use Auth;
use Session;
use App\Page;
use App\PaymentMethod;
use App\Service;
use App\Package;
use App\UserPackagePrice;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        if(getOption('front_page') == 'login'){
            return view('auth.login');
        }

        $packages = Package::where(['status' => 'ACTIVE'])->orderBy('service_id')->get();
        return view('index', compact('packages'));
    }

    public function showServices()
    {
        $services = Service::where(['status' => 'ACTIVE'])->get();
        $packages = Package::where(['status' => 'ACTIVE'])->orderBy('service_id')->get();
        if(Auth::check()){
            $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();
        }

        return view('services', compact('services', 'packages', 'userPackagePrices'));
    }

    public function showPage($slug)
    {
        
        $page = Page::where(['slug' => $slug])->firstOrFail();
        $metaTags = $page->meta_tags;
        if (Auth::check() && Auth::user()->role === 'ADMIN') {
            return view('admin.static', compact('page', 'metaTags'));
        }
        return view('static', compact('page', 'metaTags'));
    }

    public function APIDocV2()
    {
        return view('api-v2');
    }

    public function ApiDocV1()
    {
        return view('api-v1');
    }

    public function showManualPaymentForm(Request $request)
    {
        // check if payment method is not enabled then
        // abort further process
        $paymentMethod = PaymentMethod::where(['id' => 5, 'status' => 'ACTIVE'])->first();
        if (is_null($paymentMethod)) {
            abort(403);
        }
        // Bank account or other payment related details
        $details = PaymentMethod::where(['config_key' => 'bank_details', 'status' => 'ACTIVE'])->first()->config_value;

        return view('payments.bank', compact('details'));
    }

    public function changeLanguage(Request $request){
        $locale = $request->input('locale');
        \App::setLocale($locale);
        Session::put('locale', $locale);
        return redirect('/');
    }

}
