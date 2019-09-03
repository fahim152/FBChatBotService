<?php

namespace App\Http\Datatables;

use App\User;
use Illuminate\Http\Request;

class UserDatatable extends Datatable {

    private $nav = 'users';

    public function table(Request $request) {

        $this->deleteTableItem($request);

        $results = new User;

        $name = isset($request->name) ? $request->name : null;
        $email = isset($request->email) ? $request->email : null;
        $group = isset($request->group) ? $request->group : null;
        $user_group = isset($request->user_group) ? $request->user_group : null;

        $updated_from = isset($request->updated_from) ? $request->updated_from : null;
        $updated_to = isset($request->updated_to) ? $request->updated_to : null;

        if ($name) {
            $results = $results->where('name', 'like', '%'.$name.'%');
        }
        if ($email) {
            $results = $results->where('email', 'like', '%'.$email.'%');
        }
        if ($group) {
            $results = $results->whereHas('group', function($query) use ($group){
                $query->where('name', 'like', '%'.$group.'%');
            });
        }
        if ($user_group) {
            $results = $results->whereHas('group', function($query) use ($user_group){
                $query->where('id', $user_group);
            });
        }
        if ($updated_from) {
            $results = $results->where('updated_at', '>=', $this->date_filter($updated_from));
        }
        if ($updated_to) {
            $results = $results->where('updated_at', '<=', $this->date_filter($updated_to, true));
        }
        $tableColumns = [
            "",
            "name",
            "email",
            "group",
            "mobile",
            "updated_at",
            ""
        ];
        $sortColumn = $request->order[0]['column'];
        $sortDir = $request->order[0]['dir'];
        $sort_field = $tableColumns[$sortColumn];

        switch ($sort_field) {
            case "name":
                $results = $results->orderBy('name', $sortDir);
                break;
            case "email":
                $results = $results->orderBy('email', $sortDir);
                break;
            case "updated_at":
                $results = $results->orderBy('updated_at', $sortDir);
                break;
            default:
                $results = $results->orderBy('updated_at', 'desc');
                break;
        }

        $results = $results->get();

        $iTotalRecords = $results->count();
        $iDisplayLength = intval($request->length);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $data = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for ($i = $iDisplayStart; $i < $end; $i++) {
            $result = $results[$i];

            if($this->checkMenuPermission($this->nav, 'update')) {
                $edit = "<a class='btn btn-sm btn-outline blue' title='Edit' href='" . route('user_edit', [$result->id]) . "'> <i class='icon-note'></i> Edit</a>";
            }
            if($this->checkMenuPermission($this->nav, 'delete')) {
                $delete = "<a class='btn btn-sm btn-outline red table-row-delete' title='Remove' href='javascript:;' data-id='" . $result->id . "'> <i class='icon-trash'></i> Remove</a>";
            }

            $data[] = [
                $i+1,
                isset($result->name) ? $result->name : "",
                isset($result->email) ? $result->email : "",
                isset($result->group) ? $result->group->name : "",
                isset($result->mobile) ? $result->mobile : "",
                isset($result->updated_at) ? date_format($result->updated_at, 'd/m/Y') : "",
                (isset($edit) ? $edit : "") . (isset($delete) ? $delete : ""),
            ];
        }
        $this->data = $data;
        $this->draw = $sEcho;
        $this->total = $iTotalRecords;
        $this->filtered = $iTotalRecords;
        return $this->outputDatatable();
    }

    public function deleteTableItem($request) {
        if (isset($request->actionType) && $request->actionType == "delete_action") {
            $user = User::find($request->record_id);
            if ($user) {
                $user->delete();
                $this->status = "OK";
                $this->message = "User deleted successfully";
            } else {
                $this->message = "User delete failed";
            }
        }
    }

}
