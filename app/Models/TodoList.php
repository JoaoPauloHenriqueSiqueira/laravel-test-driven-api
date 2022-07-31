<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoList extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public static function boot()
    // {
    //     parent::boot();
    //     self::deleting(function($list){
    //         $list->tasks->each->delete();
    //     });
    // }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
