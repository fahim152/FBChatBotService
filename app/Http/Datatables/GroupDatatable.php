<?php

namespace App\Http\Datatables;

use App\Group;
use Illuminate\Http\Request;

class GroupDatatable extends Datatable {

    private $nav = 'groups';

    public function table(Request $request) {

        $this->deleteTableItem($request);

        $results = new Group;

        $name = isset($request->name) ? $request->name : null;

        if ($name) {
            $results = $results->where('name', 'like', '%'.$name.'%');
        }
        $tableColumns = [
            "",
            "name",
            "description",
            "",
        ];
        $sortColumn = $request->order[0]['column'];
        $sortDir = $request->order[0]['dir'];
        $sort_field = $tableColumns[$sortColumn];

        switch ($sort_field) {
            case "name":
                $results = $results->orderBy('name', $sortDir);
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
                $edit = "<a class='btn btn-sm btn-outline blue-steel' title='Edit' href='" . route('group_edit', [$result->id]) . "'> <i class='icon-note'></i> Edit</a>";
            }
            if($this->checkMenuPermission($this->nav, 'permission')) {
                $permission = "<a class='btn btn-sm btn-outline yellow-casablanca' title='Permission' href='" . route('group_permission', [$result->id]) . "'> <i class='icon-key'></i> Permission</a>";
            }
            if($this->checkMenuPermission($this->nav, 'delete')) {
                $delete = "<a class='btn btn-sm btn-outline red table-row-delete' title='Remove' href='javascript:;' data-id='" . $result->id . "'> <i class='icon-trash'></i> Remove</a>";
            }
            $data[] = [
                $i+1,
                isset($result->name) ? "<a class='' title='View' href='" . route('users_group', [$result->id]) . "'> ".$result->name."</a>" : "",
                isset($result->description) ? $result->description : "",
                (isset($result->users) ? $result->users->count() : ""),
                ((isset($result->permissions) and $result->permissions != null and $result->permissions != "null") ? "<span class='btn btn-sm green'> ✓ </span>" : "<span class='btn btn-sm red-mint'> ✗ </span>") . (isset($edit) ? $edit : "") . (isset($permission) ? $permission : "") . (isset($delete) ? $delete : ""),
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
            $group = Group::find($request->record_id);
            if ($group) {
                if($group->users->count() == 0) {
                    $group->delete();
                    $this->status = "OK";
                    $this->message = "Group deleted successfully";
                }else {
                    $this->message = "Group delete failed. Users are assigned to this group.";
                }
            } else {
                $this->message = "Group delete failed";
            }
        }
    }

}
