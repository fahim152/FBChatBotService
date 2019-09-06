<?php

namespace App\Http\Components;



trait Menu {

    private $default_icon = '<i class="icon-file"></i>';

    public function menu() {
        return [
            ['visible' => true, 'tag' => 'dashboard', 'lang_tag' => "dashboard", 'url' => route('dashboard'), 'icon' => '<i class="icon-home"></i>',],
            ['visible' => true, 'tag' => 'settings', 'lang_tag' => "settings", 'url' => route('settings'), 'icon' => '<i class="icon-settings"></i>',],
            ['visible' => true, 'tag' => 'chats', 'lang_tag' => "chats", 'url' => route('chats'), 'icon' => '<i class="icon-envelope"></i>',],
            ['visible' => true, 'tag' => 'apparels', 'lang_tag' => "apparels", 'url' => route('apparels'), 'icon' => '<i class="icon-envelope"></i>',],
            // ['visible' => true, 'tag' => 'recipients', 'lang_tag' => "recipients", 'url' => route('recipients'), 'icon' => '<i class="icon-user"></i>',],
            ['visible' => true, 'tag' => 'orders', 'lang_tag' => "orders", 'url' => route('orders'), 'icon' => '<i class="icon-user"></i>',],

            ['visible' => $this->hasItems(['users','groups']), 'tag' => 'heading', 'lang_tag' => 'operation_setup'],
            ['visible' => $this->checkMenuPermission('users'), 'tag' => 'users', 'lang_tag' => "users", 'url' => route('users'), 'icon' => '<i class="icon-user"></i>',],
            ['visible' => $this->checkMenuPermission('groups'), 'tag' => 'groups', 'lang_tag' => "groups", 'url' => route('groups'), 'icon' => '<i class="icon-key"></i>',],
        ];
    }

    public function hasItems($options)
    {
        foreach($options as $option) {
            if($this->checkMenuPermission($option)){
                return true;
            }
        }
        return false;
    }

    public function getIcons($nav, $plural = false)
    {
        foreach ($this->menu() as $item) {
            if($item['tag'] == $nav) {
                if($plural && isset($item['icons'])) {
                    return $item['icons'];
                }else {
                    return $item['icon'];
                }
            }
        }
        return $this->default_icon;
    }
}
