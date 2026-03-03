<?php

namespace App\Modules\File\Helpers;

class LocalFile
{
   // funtion to read file dire
    public static function getDirContents($dir, &$results = array()){
        $files = scandir($dir);
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = ['path'=>$path,'size'=>filesize($path)];
            } else if($value != "." && $value != "..") {
                getDirContents($path, $results);
                $results[] = ['path'=>$path,'size'=>filesize($path)];
            }
        }
        return $results;
    }

    // $fileslist = getDirContents(public_path('seeds/subfields'));
    // return ($fileslist); 
}
