<?php

namespace App\Http\Datatables;

//use Illuminate\Http\Request;
use App\Http\Components\Permission;
use App\Http\Components\Component;

class Datatable {
    use Component,Permission;

    public $status = "";
    public $draw = "";
    public $total = "";
    public $filtered = "";

    function __construct()
    {
        $this->message = "";
    }

    public function outputDatatable() {
        $records = [
            'customActionStatus' => $this->status,
            'customActionMessage' => $this->message,
            'data' => $this->data,
            'draw' => $this->draw,
            'recordsTotal' => $this->total,
            'recordsFiltered' => $this->filtered,
        ];
        return json_encode($records);
    }

    public function set($value) {
        return isset($value) ? $value : false;
    }

    public function statuses() {
        $statuses = [
            'NOT-SENT' => 'bg-default bg-font-default',
            'PENDING' => 'bg-yellow-saffron bg-font-yellow-saffron',
            'REJECTED' => 'bg-dark bg-font-dark',
            'DELIVERED' => 'bg-green-meadow bg-font-green-meadow',
            'UNDELIVERABLE' => 'bg-red-intense bg-font-red-intense',
            'DEFAULT' => 'bg-blue-steel bg-font-blue-steel',
        ];
        return $statuses;
    }

    public function label($status='')
    {
        $statuses = $this->statuses();
        $class = isset($statuses[$status]) ? $statuses[$status] : $statuses["DEFAULT"];
        return "<label class='label $class'>".$status."</label>";
    }

    public function date_filter($jstime='', $to = false)
    {
        $formate_change = str_replace('/', '-', $jstime);
        $date = date_create($formate_change);
        if($to) {
            date_add($date, date_interval_create_from_date_string('1 days'));
        }
        return date_format($date, 'Y-m-d');
    }

}
