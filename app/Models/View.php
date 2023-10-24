<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
        'is_fake',
        'ip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viewable()
    {
        return $this->morphTo('viewable');
    }

    public static function make($resourceType, $resourceId, $uId=null)
    {
        $user = auth()->user();
        $vQ = self::query()
            ->where('viewable_type', $resourceType)
            ->where('viewable_id', $resourceId);

        if ($user && $uId && $user->id == $uId) {
            // do not log views of itself
            return;
        }

        // create view record
        View::create([
            'viewable_type' => $resourceType,
            'viewable_id' => $resourceId,
            'user_id' => $user->id??null,
            'ip' =>request()->ip()
        ]);

        return;
    }
}
