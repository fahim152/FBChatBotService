<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Http\Datatables\SettingDatatable;
use Validator;

class SettingController extends Controller
{
    private $nav = 'categories';

    function __construct()
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [
            'base_url' => route('settings'),
            'dataload_url' => route('settings_load'),
            'page_title' => "settings",
            'title' => "settings",
            'titles' => "settings",
            'parentTitles' => "",
            'icon' => $this->getIcons($this->nav),
            'icons' => $this->getIcons($this->nav, true),
            'create' => true,
            'filter' => false,
            'unsortable' => "0,3",
            'columns' => [
                [ "title" => "#", "width" => "5%", "filter" => ""],
                [ "title" => "fb_app_name", "filter" => $this->filterText("fb_app_name")],
                // [ "title" => "token", "filter" => $this->filterText("token")],

                [ "title" => "updated_time", "filter" => $this->filterDateRange()],
                [ "title" => "action", "filter" => $this->filterAction()],
            ],
        ];
        $params['menus'] = $this->menu();
        $params['message'] = $this->getAlert();
        $params['messageType'] = $this->getAlertCSSClass();

        return view('table', $params)->withNav($this->nav);
    }

    public function datatable(Request $request) {
        $ajax_table = new SettingDatatable;
        return $ajax_table->table($request);
    }



    public function create() {
        $params['setting']['id'] = "";
        $params['setting']['token'] = "";
        $params['menus'] = $this->menu();
        return view('setting-create', $params)->withNav($this->nav);
    }

    public function edit(Request $request) {


            $setting = Setting::find($request->setting);

            if ($setting) {
                $params['setting'] = $setting;

                $params['lang_type'] = "edit";

                return view('setting-create', $params)->withNav($this->nav);
            }


    }

    public function update(Request $request) {

            $validation = [
                'name'          => 'required|string|max:255',
                'token'          => 'required|string',

            ];

            if (!$request->setting) {
                $validation['name']= 'required|string|max:255';
                $validation['token']= 'required|string';
            }

            $validator = Validator::make($request->all(), $validation);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->validationError();
            }

            if ($request->setting) {
                $setting = Setting::find($request->setting);
            } else {
                $setting = new Setting;
            }

            $setting->fb_app_name = $request->input('name');
            $setting->token = $request->input('token');


            if ($setting->save()) {
                $this->success();
                $default = "Setting Name '" . $setting->name . "'";
                $this->message = ($request->setting) ? "$default Updated" : "New $default Added";
                if (!$request->setting) {
                    $data['redirect_to'] = route('settings');
                    $this->setAlert($this->message);
                    $this->setAlertCSSClass("success");
                }
            }
            $this->data = isset($data) ? $data : [];

        return $this->output();
    }


}
