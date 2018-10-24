<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    /*
     * Relationships
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }
    public function participants()
    {
        return $this->hasMany('App\EventParticipant', 'purchase_id');
    }
}
