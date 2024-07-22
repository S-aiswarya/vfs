<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class LogoSm extends Component
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
        $logo_sm = Cache::get('logo_sm', function () {
            $image = null;
            if(Schema::hasTable('settings'))
            {
                $logo_setting = Setting::where('code', 'logo_small')->first();
                if($logo_setting){
                    $image = $logo_setting->value_text;
                }
            }
            
            return $image;
        });
        return view('components.logo-sm')->with('logo_sm', $logo_sm);
    }
}
