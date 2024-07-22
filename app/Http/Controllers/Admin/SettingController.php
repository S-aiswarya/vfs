<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController as Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use View, Redirect, Validator, DB, Artisan;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:site_settings', ['only' => ['index','store']]);
    }

    public function index()
    {
        $settings = Setting::where('settings_type', '!=', 'Others')->get();
        $data = [];
        foreach($settings as $setting)
        {
            $data[$setting->code] = $setting->value_text;
        }
        $other_data = Setting::where('settings_type', 'Others')->get();
        return view('admin.settings.index')->with('data', $data)->with('other_data', $other_data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if($data['settings_type'] == 'Contact' || $data['settings_type'] == 'Social Media' || $data['settings_type'] == 'Common' || $data['settings_type'] == 'Smtp' || $data['settings_type'] == 'Google')
        {
            foreach($data['settings'] as $key=>$value)
            {
                $setting = Setting::where('code', $key)->first();
                $setting->value_text = $value;
                $setting->save();
            }
        }
        elseif ($data['settings_type'] == 'Logo' || $data['settings_type'] == 'Small Logo' || $data['settings_type'] == 'Fav Icon') {
            $validator = Validator::make($data, [
                'file' => 'required|mimes:jpeg,png,webp,x-icon,svg,ico|max:2048',
            ]);

            if ($validator->fails()){
                return Redirect::back()->withInput()->withErrors($validator->errors()->all());
            }
            else
            {
                $setting_type = $data['settings_type'];
                $fileName = $request->file->getClientOriginalName();
                $file_parts = pathinfo($fileName);
                $file_ext = $file_parts['extension'];
                $file_name = $file_parts['filename'];
                $extra = uniqid();
                $fileName = $file_name . $extra . '.' . $file_ext;
                switch ($setting_type) {
                    case 'Logo':
                        $setting = Setting::where('code', 'logo')->first();
                        break;
                    
                    case 'Small Logo':
                        $setting = Setting::where('code', 'logo_small')->first();
                        break;

                    case 'Fav Icon':
                        $setting = Setting::where('code', 'fav_icon')->first();
                        break;
                }

                $request->file->move(public_path('uploads/settings'), $fileName);
                $setting->value_text = 'uploads/settings/'.$fileName;
                $setting->save();
            }
        }
        else{
            foreach ($data['code'] as $key => $value) {
                if(trim($data['value'][$key]) != '')
                {
                    $settings = Setting::where('code', $value)->first();
                    if(!$settings)
                    {
                        $settings = new Setting;
                        $settings->code = $value;
                        $settings->input_type = 'Text';
                        $settings->settings_type = 'Others';
                    }
                    $settings->value_text = $data['value'][$key];
                    $settings->save();
                }
            }
        }
        $this->clear_cache();
        return Redirect::to(route('admin.settings.index'))->withSuccess('Settings successfully saved!'); 
    }

    public function clear_cache()
    {
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        return true;
    }

}