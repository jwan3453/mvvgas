<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    //
	protected $table = 'issues';
	protected $primaryKey = 'id';
	protected $fillable = ['feature', 'location', 'description', 'diagnosed_issue','reported_issue','date_closed','date_closed','status'];
}
