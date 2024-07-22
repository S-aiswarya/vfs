<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class BladeHelper
{

    public static function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        $bytes /= (1 << (10 * $pow)); 

        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }

    public static function formatView($key, $value, $obj){
        if($key == "created_by"){
            if($obj->created_user)
                return $obj->created_user->name;
        }
        elseif($key == "updated_by"){
            if($obj->updated_user)
                return $obj->updated_user->name;
        }
        elseif($key == "featured_image_id"){
            if($obj->featured_image)
                return '<img src="'.asset($obj->featured_image->file_path).'" style="max-width:200px;"/>';
        }
        elseif($key == "banner_image_id"){
            if($obj->banner_image)
                return '<img src="'.asset($obj->banner_image->file_path).'" style="max-width:200px;"/>';
        }
        elseif($key == "og_image_id"){
            if($obj->og_image)
                return '<img src="'.asset($obj->og_image->file_path).'" style="max-width:200px;"/>';
        }
        elseif($key == "created_at" || $key == "updated_at" || $key == "deleted_at" ){
            return date('d M, Y h:i A', strtotime($value));
        }
        else{
            if(is_array($value)){
                $output = "";
                foreach($value as $key=>$item){
                    if(!is_array($item))
                        $output .= "<p>{$key}: {$item}</p>";
                }
                return $output;
            }
            else
                return $value;
        }
    }
}