<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $fillable = ['token'];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function disableToken($token = 'wialon')
    {
      $this->{$token} = null;
      return response()->json(['response' => $this->save()]);
    }
}
