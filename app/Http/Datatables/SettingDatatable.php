<?php

namespace App\Http\Datatables;

use App\Setting;
use Illuminate\Http\Request;

class SettingDatatable extends Datatable {

    private $nav = 'settings';

    public function table(Request $request) {

        $this->deleteTableItem($request);

        $results = new Setting;

        $name = isset($request->name) ? $request->name : null;

        $updated_from = isset($request->updated_from) ? $request->updated_from : null;
        $updated_to = isset($request->updated_to) ? $request->updated_to : null;

        if ($name) {
            $results = $results->where('name', 'like', '%'.$name.'%');
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

            $edit = "<a class='btn btn-sm btn-outline blue' title='Edit' href='" . route('settings_edit', [$result->id]) . "'> <i class='icon-note'></i> Edit</a>";
            $delete = "<a class='btn btn-sm btn-outline red table-row-delete' title='Remove' href='javascript:;' data-id='" . $result->id . "'> <i class='icon-trash'></i> Remove</a>";

            $data[] = [
                $i+1,
                isset($result->fb_app_name) ? $result->fb_app_name : "",
                // isset($result->token) ? $result->token : "",
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
            $setting = Setting::find($request->record_id);
            if ($setting) {
                $setting->delete();
                $this->status = "OK";
                $this->message = "Setting deleted successfully";
            } else {
                $this->message = "Setting delete failed";
            }
        }
    }

}
