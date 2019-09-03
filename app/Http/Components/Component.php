<?php

namespace App\Http\Components;

use App\Group;
use App\Office;
use App\User;
use Auth;
use Mail;
use App\Mail\NotificationEmail;
use App\Http\Components\InfobipAPI;

trait Component {
    public $validation = 1;
    public $success = false;
    public $message = "Request processing failed";
    public $data = [];
    public $errors = [];
    public $html = [];

    public function validationError() {
        $data = [
            'validation' => $this->validation,
            'errors' => $this->errors,
        ];
        return $data;
    }


    public function output() {
        $data = [
            'success' => $this->success,
            'message' => $this->message,
        ];
        if (count($this->data)) {
            $data['data'] = $this->data;
        }
        if (count($this->html)) {
            $data['html'] = $this->html;
        }

        return $data;
    }


    public function success() {
        $this->success = true;
    }


    public function setAlert($message) {
        session(['alert' => $message]);
    }


    public function setAlertPermission() {
        session(['alert' => "Currently You don't have permission to visit that page"]);
    }


    public function getAlert() {
        return (session()->has('alert') ? session()->pull('alert') : null);
    }


    public function setAlertCSSClass($type = "") {
        session(['alertType' => (($type == "success") ? "alert-success" : "alert-danger")]);
    }


    public function getAlertCSSClass() {
        return (session()->has('alertType') ? session()->pull('alertType') : "alert-danger");
    }


    public function storage_link($project_path, $file_path, $file)
    {
        $file_url = rtrim($project_path, '/') . '/storage/' . rtrim($file_path, '/') . '/' . ltrim($file, '/');
        return $file_url;
    }

    private function getGroupCodes(){
        $groups = Group::whereStatus('active')->get();
        $group_codes = [];
        if(count($groups)>0){
            foreach($groups as $g){
                array_push($group_codes, $g->code);
            }
        }
        return $group_codes;
    }

    public function getLocale(){
        return \Lang::getLocale();
    }

}
