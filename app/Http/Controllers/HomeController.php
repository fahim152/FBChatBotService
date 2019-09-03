<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $nav = 'dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       return redirect(route('dashboard'));
    }

    public function lang(Request $request)
    {
        \App::setLocale($request->lang);
        session(['lang' => \App::getLocale()]);
    }

    public function dashboard() {
        $params['message']      = $this->getAlert();
        $params['messageType']  = $this->getAlertCSSClass();
        return view('dashboard', $params)->withNav($this->nav);
    }

    public function denied(Request $request)
    {
        return view('denied');
    }
}
