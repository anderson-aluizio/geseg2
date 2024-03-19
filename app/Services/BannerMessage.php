<?php

namespace App\Services;


class BannerMessage
{

    static public function message($message  = '', $type = 'success')
    {
        request()->session()->flash('flash.banner', $message);
        request()->session()->flash('flash.bannerStyle', $type);
    }

    static public function alertMessage($message  = '', $type = 'success')
    {
        request()->session()->flash('flash.alert', $message);
        request()->session()->flash('flash.alertStyle', $type);
    }
}
