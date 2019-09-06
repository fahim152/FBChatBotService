<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Recipient;
use App\Http\Datatables\RecipientDatatable;

class RecipientController extends Controller
{
    private $nav = 'recipients';

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
            'base_url' => route('recipients'),
            'dataload_url' => route('recipients_load'),
            'page_title' => "recipients",
            'title' => "recipients",
            'titles' => "recipients",
            'parentTitles' => "",
            'icon' => $this->getIcons($this->nav),
            'icons' => $this->getIcons($this->nav, true),
            'create' => false,
            'filter' => false,
            'unsortable' => "0,3",
            'columns' => [
                [ "title" => "#", "width" => "5%", "filter" => ""],
                // [ "title" => "name", "filter" => $this->filterText("name")],
                [ "title" => "recipients_id", "filter" => $this->filterText("recipients_id")],
                [ "title" => "status", "filter" => $this->filterText("status")],
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
        $ajax_table = new RecipientDatatable;
        return $ajax_table->table($request);
    }


    public function update(Request $request) {

            if ($request->category) {
                $recipient = Recipient::find($request->category);
            } else {
                $recipient = new Recipient;
            }

            $recipient->recipient_id = $request->input('recipient_id');
            $recipient->status = $request->input('status');


            if ($recipient->save()) {
                $this->success();
                $data[] = '';


            }
            $this->data = isset($data) ? $data : [];

        return $this->output();
    }

    public function statusCheck($id){
        $recipients_status = Recipient::where('recipient_id', $id)->pluck('status')->first();;
        return $recipients_status;
    }

    public function statusChange($id, $status){

        $recipient = Recipient::where('recipient_id', $id)->first();

        $recipient->status = $status;

        if($recipient->save()){
            $this->success();
        }

        return $this->output();
    }

}
