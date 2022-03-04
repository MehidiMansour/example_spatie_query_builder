<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    /*
|------------------------------------------------------------------------------------
|Relations
|------------------------------------------------------------------------------------
*/

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
    /*
|------------------------------------------------------------------------------------
| Scope
|------------------------------------------------------------------------------------
*/
    public function scopeMine($q)
    {
        return $q->where('user_id', auth()->id());
    }
    /*
|------------------------------------------------------------------------------------
| Attributes
|------------------------------------------------------------------------------------
*/
    public function getIsMineAttribute()
    {
        return $this->user_id === auth()->id();
    }
}
