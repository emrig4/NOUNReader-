<?php
use  App\Modules\Setting\Models\Setting;

if (! function_exists('setting')) {
    
    function setting($name = null, $value=null,  $default = null)
    {
        if (is_null($name)) {
           $settings =  Setting::all();
           $collection = collect($settings)->map(function ($setting) {
                return [$setting->name => $setting->value];
            });
           return $collection;
        }

        if($name && $value){
            return Setting::set($name, $value);
        }

        if (is_array($name)) {
            return  Setting::setMany($name);
        }

        try {
            return Setting::get($name, $default);
        } catch (PDOException $e) {
            return $default;
        }
    }
}

if (! function_exists('get_allowed_file_types')) {
    
    function get_allowed_file_types($for='upload')
    {
        //mpga
        /*$allowed=setting('allowed_file_types',['pdf','epub']);
        if(in_array('mp3',$allowed)){
            $allowed[]='mpga';
        }*/
        $allowed=['pdf','epub','docx','doc','txt','pptx','ppt','xls','xlsx'];
        if($for=='audio'){
            $allowed=['mp3','wav','mpga'];
        }
        return $allowed;
        
    }
}
