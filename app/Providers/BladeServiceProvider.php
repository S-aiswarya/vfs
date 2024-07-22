<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        Blade::directive('fieldshow', function ($input) {
            $input = explode('-', $input);
            $page = $input[0];
            $field = $input[1];
            $field_array = config("admin.fields.{$page}");
            $expression = 0;
            if(in_array($field, $field_array))
                $expression = 1;
            return "<?php if($expression) : ?>";
        });

        Blade::directive('endfieldshow', function () {
            return '<?php endif; ?>';
        });
    }
}
