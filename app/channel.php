<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class channel extends Model
{
    protected $fillable = ['name','slug'];

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
