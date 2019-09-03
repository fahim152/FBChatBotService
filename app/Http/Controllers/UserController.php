<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use Hash;
use App\User;
use App\Group;
use App\Http\Datatables\UserDatatable;
use Illuminate\Http\Request;

class UserController extends Controller {

    private $nav = 'users';

    function __construct()
    {
    }

    public function index(Request $request) {
        if(!$this->checkMenuPermission($this->nav, 'show')) {
            $this->setAlertPermission();
            return redirect(route("home"));
        }
        if(isset($request->user_group)) {
            $base_url = route('users_group', ['user_group' => $request->user_group]);
            $dataload_url = route('users_group_load', ['user_group' => $request->user_group]);
            $group = Group::find($request->user_group);
            $parentTitles = ($group != null) ? $group->name : "";
        }else {
            $base_url = route('users');
            $dataload_url = route('users_load');
            $parentTitles = "";
        }

        $params = [
            'base_url' => $base_url,
            'dataload_url' => $dataload_url,
            'title' => "user",
            'titles' => "users",
            'parentTitles' => $parentTitles,
            'icon' => $this->getIcons($this->nav),
            'icons' => $this->getIcons($this->nav, true),
            'create' => $this->checkMenuPermission($this->nav, 'create'),
            'filter' => true,
            'unsortable' => "0,3,4,5",
            'columns' => [
                [ "title" => "#", "width" => "5%", "filter" => ""],
                [ "title" => "name", "filter" => $this->filterText("name")],
                [ "title" => "email", "filter" => $this->filterText("email")],
                [ "title" => "group", "filter" => $this->filterText("group")],
                [ "title" => "mobile", "filter" => $this->filterText("mobile")],
                [ "title" => "updated_time", "filter" => $this->filterDateRange()],
                [ "title" => "action", "filter" => $this->filterAction()],
            ],
        ];
        $params['message'] = $this->getAlert();
        $params['messageType'] = $this->getAlertCSSClass();

        return view('table', $params)->withNav($this->nav);
    }

    public function datatable(Request $request) {
        $ajax_table = new UserDatatable;
        return $ajax_table->table($request);
    }

    public function profile(){

        $profile = Auth::user();

        $params = [
            'profile' => $profile
            
        ];

        return view('profile', $params);
    }
    public function create() {
        if(!$this->checkMenuPermission($this->nav, 'create')) {
            $this->setAlertPermission();
            return redirect(route($this->nav));
        }
        $params['user']['id'] = "";
        $params['groups'] = Group::all();
        $params['lang_type'] = "create";

        return view('users-create', $params)->withNav($this->nav);
    }

    public function edit(Request $request) {
        if(!$this->checkMenuPermission($this->nav, 'update')) {
            $this->setAlertPermission();
        }else {
            $user = User::find($request->user);
            $params['user'] = $user;
            $params['groups'] = Group::all();
            $params['lang_type'] = "edit";

            if ($user) {
                return view('users-create', $params)->withNav($this->nav);
            }
        }
        return redirect(route($this->nav));
    }

    public function update(Request $request) {
        if($this->checkMenuPermission($this->nav, 'create') || $this->checkMenuPermission($this->nav, 'update')) {
            $validation = [
                'group'         => 'required',
                'name'          => 'required|string|max:255',
                'mobile'        => 'nullable|numeric|min:1|max:11',
            ];
            if (!$request->user) {
                $validation['email']= 'required|string|email|max:255|unique:users';
                $validation['password']= 'required|string|max:255';
            }

            $validator = Validator::make($request->all(), $validation);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->validationError();
            }
            if ($request->user) {
                $user = User::find($request->user);
            } else {
                $user = new User;
            }

            $user->group_id = $request->input('group');

            $user->name = $request->input('name');

            $user->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $user->name)));

            $user->mobile = $request->input('mobile');

            if($request->has('email')) {
                $user->email = $request->input('email');
            }
            if($request->has('password')) {
                $user->password = bcrypt($request->input('password'));
            }

            if ($user->save()) {
                $this->success();
                $default = " User Name '" . $user->name . "'";
                $this->message = ($request->user) ? "$default Updated" : "New $default Added";
                if (!$request->user) {
                    $data['redirect_to'] = route('users');
                    $this->setAlert($this->message);
                    $this->setAlertCSSClass("success");
                }
            }
            $this->data = isset($data) ? $data : [];
        }
        return $this->output();
    }



    public function profileUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255',
            'mobile'      => 'required|string|max:11',
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->validationError();
        }

        $user = User::find(Auth::user()->id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->mobile = $request->input('mobile');

        if ($user->save()) {
            $this->success();
            $this->message = "Profile Updated";
        }
        return $this->output();

    }

    public function pictureUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'picture' => 'required|mimes:jpeg,bmp,png',
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->validationError();
        }

        $user = User::find(Auth::user()->id);
        $pictureFile = $request->file('picture');
        $picture;

        if($user->group->id == 3 and $user->group->name == "Deputy Commissioner"){
            $picture = $this->saveInStorage($pictureFile, 'images', $user->district->name.'_DC');
            $image = $pictureFile;
            $imageName = $user->district->name.'_DC.'.$image->getClientOriginalExtension();
            $image->move(public_path('/images'), $imageName);
        }else{
            $picture = $this->saveInStorage($pictureFile, 'images');
        }
        //$picture = $this->saveInStorage($pictureFile, 'images');

        $user->picture = $picture;

        if ($user->save()) {
            $this->success();
            $this->message = "Picture Updated";
        }
        return $this->output();
    }

    public function signatureUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'signature' => 'required|mimes:jpeg,bmp,png',
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->validationError();
        }

        $user = User::find(Auth::user()->id);

        // Save 'signature' //
        $signature = $this->saveInStorage($request->file('signature'),'signatures');

        $user->signature = $signature;

        if ($user->save()) {
            $this->success();
            $this->message = "Signature Updated";
        }
        return $this->output();

    }

    public function passwordUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255',
            'confirm_password' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->validationError();
        }
        $user = User::find(Auth::user()->id);

        if (Hash::check($request->input('current_password'), $user->getAuthPassword())) {
            if($request->input('new_password') != $request->input('confirm_password')) {
                $this->message = "Password mismatched!";
            }

            $user->password = bcrypt($request->input('new_password'));

            if ($user->save()) {
                $this->success();
                $this->message = "Password Updated";
            }
        }else{
            $this->message = "You Entered Current Password Wrong!";
        }
        return $this->output();
    }

}
