<?php

namespace App\Traits;

use App\Models\View;

trait Viewable
{
    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function saveView()
    {
        $user = auth()->user();

        if ($user && $this->user_id && $user->id == $this->user_id) {
            return;
        }

        $ip = request()->ip();
        $view = $this->views()->whereJsonContains('ips', $ip)->first();

        // attach user to existing view record
        if ($view && !$view->user_id && $user) {
            $view->update([
                'user_id' => $user->id
            ]);
        }

        // get existting view record of this user
        if (!$view && $user) {
            $view = $this->views()->where('user_id', $user->id)->first();
        }

        // increment views count of existing view record
        if ($view) {
            $view->increment('count');
            return;
        }

        // create view record
        $this->views()->create([
            'user_id' => $user->id??null,
            'ips' => [$ip]
        ]);

        return;
    }
}
