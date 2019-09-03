<?php

namespace App\Http\Components;

use Auth;
use App\Group;

trait Permission {

    public function permissions() {
        return [
            [
                'lang_tag' => "profile", 'tag' => "profile",
                'items' => [
                    ['visible' => $this->checkPermission('profile', 'change'), 'tag' => 'change', 'lang_tag' => "profile", 'lang_tag_option' => "change",],
                    ['visible' => $this->checkPermission('profile', 'password'), 'tag' => 'password', 'lang_tag' => "password", 'lang_tag_option' => "change",],
                    ['visible' => $this->checkPermission('profile', 'signature'), 'tag' => 'signature', 'lang_tag' => "signature", 'lang_tag_option' => "change",],
                ],
            ],
            [
                'lang_tag' => "viewing_menu", 'tag' => "viewing_menu",
                'items' => [
                    ['visible' => $this->checkPermission('users'), 'tag' => 'users', 'lang_tag' => "users", 'lang_tag_option' => "view",],
                    ['visible' => $this->checkPermission('groups'), 'tag' => 'groups', 'lang_tag' => "groups", 'lang_tag_option' => "view",],
                ],
            ],
        ];
    }

    
    public function checkMenuPermission($key, $subkey = "")
    {
        return $this->checkPermission('viewing_menu', $key);
    }

    /* This function if a group of user has permission to access something,
     * Ex: access to some "status" to change or access to some page, Ex: reports.
     * This function returns a Boolean value.
     *
     * @param $key String
     * @param $subkey String
     * @param $group_code String
     *
     * @return Boolean
     */
     public function checkPermission($key, $subkey = "", $group_code=null) {
        $user_group = !isset($group_code) ? ( isset(Auth::user()->group) ? Auth::user()->group : null ) : Group::whereCode($group_code)->first();
        /* OLD CODE : $group_permissions = isset(Auth::user()->group) ? Auth::user()->group->permissions : null; */
        $group_permissions = isset($user_group) ? $user_group->permissions : null;
        if(isset($group_permissions)) {
            $permissions = json_decode($group_permissions, true);
            if (isset($permissions) && is_array($permissions)) {
                if(key_exists($key, $permissions)) {
                    if($subkey != "") {
                        return in_array($subkey, $permissions[$key]);
                    }
                    return true;
                }
            }
        }
        return false;
    }

    /* This function returns all the statuses assigned to a group of users which they have access to see in datatable.
     * This function returns a String array of statuses.
     * Here the default value for "$key" is "_access_view".
     *
     * @param $key String
     * @param $group Group Object
     *
     * @return $user_view_permission_statuses String Array
     */
    public function getViewPermissions($key, $group){
        $user_view_permission_statuses = [];
        if(isset($group)){
            $group_permissions = $group->permissions;
            if(isset($group_permissions)) {
                $permissions = json_decode($group_permissions, true);
                if (is_array($permissions)) {
                    if(key_exists($key, $permissions)) {
                        $user_view_permission_statuses = $permissions[$key];
                    }
                }
            }
        }
        return $user_view_permission_statuses;
    }
}
