<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Chat;
use App\Http\Datatables\ChatDatatable;

use Validator;

class ChatController extends Controller
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
            'base_url' => route('chats'),
            'dataload_url' => route('chats_load'),
            'page_title' => "chats",
            'title' => "chats",
            'titles' => "chats",
            'parentTitles' => "",
            'icon' => $this->getIcons($this->nav),
            'icons' => $this->getIcons($this->nav, true),
            'create' => true,
            'filter' => false,
            'unsortable' => "0,3",
            'columns' => [
                [ "title" => "#", "width" => "5%", "filter" => ""],
                [ "title" => "message_like", "filter" => $this->filterText("fb_app_name")],
                [ "title" => "reply_with", "filter" => $this->filterText("reply_with")],
                [ "title" => "app_token", "filter" => $this->filterText("app_token")],
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
        $ajax_table = new ChatDatatable;
        return $ajax_table->table($request);
    }

    public function create() {
        $settings  = Setting::all();
        $params['chat']['id'] = "";
        $params['settings'] = $settings;

        // $params['setting']['token'] = "";
        $params['menus'] = $this->menu();
        return view('chat-create', $params)->withNav($this->nav);
    }

    public function edit(Request $request) {
            $chat = Chat::find($request->setting);
            $settings  = Setting::all();
            if ($chat) {
                $params['chat'] = $chat;
                $params['setting'] = $settings;
                $params['lang_type'] = "edit";

                return view('chat-create', $params)->withNav($this->nav);
            }

    }

    public function update(Request $request) {

            $validation = [
                'message_like'    => 'required|string|max:255',
                'reply_with'          => 'required|string',

            ];

            if (!$request->chat) {
                $validation['message_like']= 'required|string|max:255';
                $validation['reply_with']= 'required|string';
            }

            $validator = Validator::make($request->all(), $validation);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->validationError();
            }

            if ($request->chat) {
                $chat = Chat::find($request->chat);
            } else {
                $chat = new Chat;
            }

            $chat->message_like = $request->input('message_like');
            $chat->reply_with = $request->input('reply_with');
            $chat->setting_id = $request->input('setting_id');

            if ($chat->save()) {
                $this->success();
                $default = "Chat Message Like'" . $chat->message_like . "'";
                $this->message = ($request->chat) ? "$default Updated" : "New $default Added";
                if (!$request->chat) {
                    $data['redirect_to'] = route('chats');
                    $this->setAlert($this->message);
                    $this->setAlertCSSClass("success");
                }
            }
            $this->data = isset($data) ? $data : [];

        return $this->output();
    }


}
