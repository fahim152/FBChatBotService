<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apparel;
use App\Setting;
use App\Http\Datatables\ApparelDatatable;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ApparelController extends Controller
{   private $nav = 'apparels';

    public function index()
    {
        $params = [
            'base_url' => route('apparels'),
            'dataload_url' => route('apparels_load'),
            'page_title' => "apparels",
            'title' => "apparels",
            'titles' => "apparels",
            'parentTitles' => "",
            'icon' => $this->getIcons($this->nav),
            'icons' => $this->getIcons($this->nav, true),
            'create' => true,
            'filter' => true,
            'unsortable' => "0,3",
            'columns' => [
                [ "title" => "#", "width" => "5%", "filter" => ""],
                [ "title" => "model", "filter" => $this->filterText("name")],
                [ "title" => "quantity", "filter" => $this->filterText("quantity")],
                [ "title" => "image", "filter" => $this->filterText("keywords")],
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
        $ajax_table = new ApparelDatatable;
        return $ajax_table->table($request);
    }

    public function create() {
        $params['apparel']['id'] = "";
        $params['apparel']['image_link'] = "";
        $params['menus'] = $this->menu();
        return view('apparel-create', $params)->withNav($this->nav);
    }

    public function edit(Request $request) {
            $apparel = Apparel::find($request->apparel);

            if ($apparel) {
                $params['apparel'] = $apparel;
                $params['apparel_picture'] = asset('') . $apparel['image_link'];
                $params['lang_type'] = "edit";

                return view('apparel-create', $params)->withNav($this->nav);
            }


    }

    public function update(Request $request) {

            $validation = [
                'model'          => 'required|string|max:255',
                'quantity'          => 'required|integer',
            ];

            if (!$request->apparel) {
                $validation['model']= 'required|string|max:255';
                $validation['quantity']= 'required|integer';
            }

            $validator = Validator::make($request->all(), $validation);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->validationError();
            }

            if ($request->apparel) {
                $apparel = Apparel::find($request->apparel);
            } else {
                $apparel = new Apparel;
            }

            $apparel->model = $request->input('model');
            $apparel->quantity = $request->input('quantity');



            if($request->file('picture')){
                $path = storage_path('public/apperal_picture/');

                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }
                $image_path = Storage::disk('public')->put('apperal_picture', $request->file('picture'));
                $apparel->image_link = isset($image_path) ?  "storage/".$image_path : "";
            }

            // if($request->file('picture')){

            // $image_path = Storage::disk('public')->putFileAs('category_picture', $request->file('picture'), $category->name .".png" );
            // $category->picture = isset( $image_path) ? "storage/" . $image_path :  $category->picture;

            // }
            if ($apparel->save()) {
                $this->success();
                $default = " apparel Name '" . $apparel->model . "'";
                $this->message = ($request->apparel) ? "$default Updated" : "New $default Added";
                if (!$request->apparel) {
                    $data['redirect_to'] = route('apparels');
                    $this->setAlert($this->message);
                    $this->setAlertCSSClass("success");
                }
            }
            $this->data = isset($data) ? $data : [];

        return $this->output();
    }

}
