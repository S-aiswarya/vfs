<?php 
namespace App\Http\Controllers\Admin;

use App\Enum\StageType;
use App\Http\Controllers\Controller;
use App\Traits\S3;

use function PHPSTORM_META\map;

class BaseController extends Controller {
    use S3;
    protected $route, $views;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->route = $this->views = 'admin';
    }

    public static function slug($slug){
        return strtolower(preg_replace( '/[-+()^ $%&.*~]+/', '-', $slug));
    }

    protected function uploadImage($request, $file_name, $file_path, $file=null){
        $image = null;
        if($request->{$file_name.'_removed'} && $file){
            $this->fileDeleteS3($file);
            $file = null;
        }
        if ($request->hasFile($file_name)) {
            $upload = $this->fileUploadS3($request->file($file_name), $file_path);
            if($upload['file'])
                $image = $upload['file'];
        }
        else
            $image = $file;

        return $image;
    }

    protected function _renderEdit($obj, $message){
        $html = $this->edit(encrypt($obj->id))->render();
        return ['title'=>'Edit '.$obj->name, 'html' => $html, 'message' => $message];
    }

    protected function getStageTypes(){
        $types = StageType::cases();
        $types = array_map(function($type){
            return $type->value;
        }, $types);
        return $types;
    }

}