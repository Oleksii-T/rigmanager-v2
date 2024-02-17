<?php

// generale unique slug based on provided array
if (!function_exists('makeSlug')) {
    function makeSlug($str, $check = [])
    {
        $slug = Str::slug($str);

        if (in_array($slug, $check)) {
            $rand = 2;
            while (true) {
                if (!in_array($slug . '-' . $rand, $check)) {
                    $slug = $slug . '-' . $rand;
                    break;
                }
                $rand++;
            }
        }

        return $slug;
    }
}

// get user avatar with fallback image
if (!function_exists('userAvatar')) {
    function userAvatar($user=null) {
        $user ??= auth()->user();
        $default = asset('icons/emptyAva.svg');

        if (!$user) {
            return $default;
        }

        return $user->avatar ? $user->avatar->compressed(200, 200) : $default;
    }
}

// get alphabet representation of integer
if (!function_exists('intToAlphabet')) {
    function intToAlphabet($number) {
        $result = '';
        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $result = chr(65 + $remainder) . $result; // 65 is the ASCII code for 'A'
            $number = ($number - $remainder - 1) / 26;
        }
        return $result;
    }
}

if (!function_exists('isdev')) {
    function isdev() {
        if (isset($_GET['rmforcedebug63'])) {
            return true;
        }

        $ips = [
            '213.174.29.197',
            '127.0.0.1',
        ];

        try {
            return in_array(request()->ip(), $ips);
        } catch (\Throwable $th) {
            return false;
        }
    }
}


if (!function_exists('devdd')) {
    function devdd($text, $array=[]) {
        if (!isdev()) {
            return;
        }

        dd($text, $array);
    }
}

if (!function_exists('devdump')) {
    function devdump($text, $array=[]) {
        if (!isdev()) {
            return;
        }

        dump($text, $array);
    }
}

// transform snake\kebab\camel case to user friendly string
if (!function_exists('readable')) {
    function readable(string|null $s, $upperCaseEach=false) {
        if (!$s) {
            return $s;
        }
        if (str_contains($s, '-')) {
            $s = str_replace('-', ' ', $s);// kebab case
        } else if (str_contains($s, '_')) {
            $s = str_replace('_', ' ', $s);// snake case
        } else {
            $s = strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $s));// camel case
        }

        return $upperCaseEach ? ucwords($s) : ucfirst($s);
    }
}

// route with appended current query params
if (!function_exists('qroute')) {
    function qroute($route, $data=null) {
        $route = $data ? route($route, $data) : route($route);
        $params = $_GET;
        $q = http_build_query($_GET);
        $route .= '?';
        $route .= $q;

        return $route;
    }
}

// print some message to separate log file
if (!function_exists('dlog')) {
    function dlog(string $text, array $array=[]) {
        return \Log::channel('dev')->info($text, $array);
    }
}

// add flash notif for user
if (!function_exists('flash')) {
    function flash(string $message, $type=true) {
        session()->flash($type ? 'message-success' : 'message-error', $message);
    }
}

// get current flash to display
if (!function_exists('getActiveFlash')) {
    function getActiveFlash() {
        $m = session()->has('message-success');
        if ($m) {
            return [
                'level' => 'success',
                'message' => session()->get('message-success')
            ];
        }

        $m = session()->has('message-error');
        if ($m) {
            return [
                'level' => 'error',
                'message' => session()->get('message-error')
            ];
        }

        $m = session('status');
        if ($m) {
            return [
                'level' => 'success',
                'message' => $m
            ];
        }

        return null;
    }
}

// get localized countries array
if (!function_exists('countries')) {
    function countries() {
        foreach (config('countries') as $c) {
            $countries[$c] = trans("countries.$c");
        }

        asort($countries);

        return $countries;
    }
}

// get currencies array
if (!function_exists('currencies')) {
    function currencies($code=null) {
        $all = config('currencies');

        return $code ? $all[$code] : $all;
    }
}

// get general info for activity log creation
if (!function_exists('infoForActivityLog')) {
    function infoForActivityLog() {
        $location = null;
        $ip = request()->ip();
        $agentString = request()->header('User-Agent');
        $agentInfo = [];
        $requestParams = request()->all();
        unset($requestParams['_token']);

        if ($ip != '127.0.0.1') {
            try {
                // Get the current log level
                // $originalLogLevel = \Illuminate\Support\Facades\Log::getLogger()->getHandlers()[0]->getLevel();
        
                // Set the log level to emergency to suppress all other log levels
                // \Illuminate\Support\Facades\Log::getLogger()->getHandlers()[0]->setLevel(\Monolog\Logger::EMERGENCY);
        
                $location = config('location.testing.enabled')
                    ? null
                    : \Stevebauman\Location\Facades\Location::get($ip)->countryCode;

                // Restore the original log level
                // \Illuminate\Support\Facades\Log::getLogger()->getHandlers()[0]->setLevel($originalLogLevel);
            } catch (\Throwable $th) {
                \Log::error("Can not detect location for activity log ($ip): " . $th->getMessage());
            }
        }

        try {
            $agent = new \Jenssegers\Agent\Agent();
            $agent->setUserAgent($agentString);
            $agentInfo = [
                'languages' => $agent->languages(),
                'device' => $agent->device(),
                'platform' => $agent->platform(),
                'platform_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'is_robot' => $agent->isRobot(),
                'robot' => $agent->robot(),
            ];
        } catch (\Throwable $th) {
            \Log::error('Can not detect agent info for activity log: ' . $th->getMessage());
        }

        $info = [
            'ip' => $ip,
            'url' => request()->fullUrl(),
            'from' => request()->headers->get('referer'),
            'location' => $location,
            'agent' => $agentString,
            'agent_info' => $agentInfo,
            'request_params' => $requestParams,
        ];

        return ['general_info' => $info];
    }
}

// case insensitive array_unique
if (!function_exists('array_iunique')) {
    function array_iunique($array) {
        return array_intersect_key(
            $array,
            array_unique( array_map( "strtolower", $array ) )
        );
    }
}

// string of author name to display for guests and unsubs
if (!function_exists('maskedUserName')) {
    function maskedUserName() {
        return '____';
    }
}

// get string from exeption
if (!function_exists('exceptionAsString')) {
    function exceptionAsString($th) {
        return $th->getMessage() . '. Trace: ' . $th->getTraceAsString();
    }
}

// case insensitive array_unique
if (!function_exists('postsMustBeApproved')) {
    function postsMustBeApproved() {
        return \App\Models\Setting::get('hide_pending_posts', true, true);
    }
}

// get readable last json error
if (!function_exists('_json_last_error')) {
    function _json_last_error()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return 'No errors';
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}

if (!function_exists('strposX')) {
    function strposX($haystack, $needle, $number = 0)
    {
        return strpos($haystack, $needle,
            $number > 1 ?
            strposX($haystack, $needle, $number - 1) + strlen($needle) : 0
        );
    }
}
