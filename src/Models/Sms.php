<?php

namespace Isaac\Smstool\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
     protected $fillable=['to','body','type'];
}
