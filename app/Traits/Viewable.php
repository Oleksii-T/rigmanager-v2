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
        View::make(get_class($this), $this->id, $this->user_id);
    }
}
