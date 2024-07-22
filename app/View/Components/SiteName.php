<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SiteName extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $site_name = Cache::get('site_name', function () {
            $name = null;
            if(Schema::hasTable('settings'))
            {
                $name_setting = Setting::where('code', 'site_name')->first();
                if($name_setting){
                    $name = $name_setting->value_text;
                }
            }
            
            return $name;
        });
        return view('components.site-name')->with('site_name', $site_name);
    }
}
