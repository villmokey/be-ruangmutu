<?php



use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Str;

if (! function_exists('auto_asset')) {

    /**
     * Alternative to Laravel default asset() helper
     * Generate an asset path for the application and auto detect current schema http/https.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function auto_asset(string $path, bool $secure = false) : string
    {
        $env    = (boolean) env('HTTPS', true);
        $secure = $env ? $env : $secure;

        return app('url')->asset($path, $secure);
    }
}

if (! function_exists('generate_random_string')) {

    /**
     * Random string generator with prefix and suffix
     *
     * @param int  $length
     * @param string  $prefix
     * @param string  $suffix
     * @param string  $seed | string source
     * @return string
     */
    function generate_random_string(int $length = 6, string $prefix = null, string $suffix = null, string $seed = null) : string
    {
        $characters         = ($seed) ? $seed : '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength   = strlen($characters);
        $randomString       = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $prefix . $randomString . $suffix;
    }
}

if (! function_exists('default_value')) {

    /**
     * @param string|int $value
     * @return string|int
     */
    function default_value($value) {
        return empty($value) ? config('site.default.value') : $value;
    }
}

if (! function_exists('default_img')) {

    /**
     * @param string $value
     * @return string
     */
    function default_img($value = null) {
        return empty($value) ? auto_asset(config('site.default.img')) : $value;
    }
}

if (! function_exists('default_avatar')) {
    /**
     * @param string $value
     * @return string
     */
    function default_avatar($value) {
        return empty($value) ? auto_asset(config('site.default.avatar')) : $value;
    }
}

if (! function_exists('is_active')) {
    /**
     * Is active label formatter
     *
     * @param boolean  $active
     * @return string
     */
    function is_active($active) {
        $class  = 'label label-default';
        $text   = __('No');
        if ($active) {
            $class = 'label label-success';
            $text   = __('Yes');
        }

        return "<span class=\"{$class}\">" . $text . "</span>";
    }
}

if (! function_exists('apiAuth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string  $guard
     * @return Factory|Guard|StatefulGuard
     */
    function apiAuth($guard = 'api')
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }

        return app(AuthFactory::class)->guard($guard);
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('getRomanNumberMonth')) {
    function getRomanNumberMonth($month): string
    {
        switch ($month){
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}

if (!function_exists('generateOtpNumber')) {
    function generateOtpNumber($length = 4)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
