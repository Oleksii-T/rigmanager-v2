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
        $default = asset('img/empty-avatar.jpeg');
        if (!$user) {
            return $default;
        }

        return $user->avatar ? $user->avatar : $default;
    }
}

// transform snake\kebab\camel case to user friendly string
if (!function_exists('readable')) {
    function readable(string $s, $upperCaseEach=false) {
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

// print some message to separate log file
if (!function_exists('dlog')) {
    function dlog(string $text, array $array=[]) {
        return \Log::channel('dev')->info($text, $array);
    }
}
