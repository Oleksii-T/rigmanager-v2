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

        return $user->avatar ? $user->avatar->url : $default;
    }
}

if (!function_exists('isdev')) {
    function isdev() {
        if (isset($_GET['rmforcedebug63'])) {
            return true;
        }

        $ips = [
            '213.174.29.7',
            '127.0.0.1',
        ];

        try {
            return in_array(request()->ip(), $ips);
        } catch (\Throwable $th) {
            return false;
        }
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
        session()->flash($type ? 'message-success' : 'messsage-error', $message);
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
