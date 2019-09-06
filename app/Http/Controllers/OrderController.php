<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Http\Datatables\OrderDatatable;

class OrderController extends Controller
{
    private $nav = 'orders';

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
            'base_url' => route('orders'),
            'dataload_url' => route('orders_load'),
            'page_title' => "orders",
            'title' => "orders",
            'titles' => "orders",
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
                [ "title" => "phone", "filter" => $this->filterText("status")],
                [ "title" => "apparel_model", "filter" => $this->filterText("apparel_model")],
                [ "title" => "apparel_image", "filter" => $this->filterText("apparel_image")],
                [ "title" => "updated_time", "filter" => $this->filterDateRange()],

            ],
        ];
        $params['menus'] = $this->menu();
        $params['message'] = $this->getAlert();
        $params['messageType'] = $this->getAlertCSSClass();

        return view('table', $params)->withNav($this->nav);
    }

    public function datatable(Request $request) {
        $ajax_table = new OrderDatatable;
        return $ajax_table->table($request);
    }


    public function update($id = null, $sts = null, $phn = null, $apparal_id = null) {

           $order = Order::where('recipient_id', $id)->get();

            if(empty($order)) {
                $order = new Order;
            }

            $order->recipient_id = isset($id) ? $id : $order->recipient_id;
            $order->status = isset($sts) ? $sts : $order->status;
            $order->phone = isset($phn) ? $phn : $order->phone;
            $order->apparal_id = isset($apparal_id) ? $apparal_id : $order->apparal_id;
            if ($order->save()) {
                $this->success();
                $data[] = '';


            }
            $this->data = isset($data) ? $data : [];

        return $this->output();
    }

    public function statusCheck($id){
        $recipients_status = Order::where('recipient_id', $id)->pluck('status')->first();;
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
