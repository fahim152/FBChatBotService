<?php

namespace App\Http\Components;

trait TableFilter {

    public function filterText($name) {
        $place_order = ucwords(str_replace("_", " ", $name));
        return '<input type="text" class="form-control form-filter input-sm" name="' . $name . '" placeholder="'. $place_order .'">';
    }

    public function filterSelect($name, $options = [])
    {
        $html = '<select name="'.$name.'" class="form-control form-filter input-sm"><option value="">Select...</option>';
        foreach ($options as $key => $option) {
            $html .= '<option value="' .$key. '">'. $option .'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public function filterDateRange($inline = false) {
        $icon = '<span class="input-group-btn"><button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button></span>';
        $from_field = '<input type="text" class="form-control form-filter input-sm" readonly name="updated_from" placeholder="From">';
        $to_field = '<input type="text" class="form-control form-filter input-sm" readonly name="updated_to" placeholder="To">';
        $html = "";
        $html .= ($inline) ? '<form class="form-inline" role="form">' : '';
        $html .= '<div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">' . (($inline) ? ($icon . $from_field) : ($from_field . $icon)) . '</div>' .
            '<div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">' . $to_field . $icon . '</div>';
        $html .= ($inline) ? '</form>' : '';
        return $html;
    }

    public function filterAction($inline = false) {
        $search = '<button class="btn btn-sm green-steel filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>';
        $reset = '<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Reset</button>';
        if($inline) {
            return '<div class="flex-group">' . $search . $reset . '</div>';
        }else {
            return '<div class="margin-bottom-5">' . $search . '</div>' . $reset;
        }
    }

}
