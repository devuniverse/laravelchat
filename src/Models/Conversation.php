<?php

namespace Devuniverse\Laravelchat\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_one', 'user_two', 'entity_id'
    ];

    /**
     * The rules attributes.
     *
     * @var array
     */
    protected static $rules = [
        //
    ];

    /**
     * The rules getter.
     *
     * @return array
     */
    public static function rules()
    {
        return self::$rules;
    }

    /**
     * Get conversation messages.
     *
     * @return collection
     */
    public function messages()
    {
        return $this->hasMany('Devuniverse\Laravelchat\Models\Message', 'conversation_id');
    }

    /**
     * Get conversation first user
     *
     * @return collection
     */
    public function userOne()
    {
        return $this->belongsTo(config('messenger.user.model', 'App\User'),  'user_one');
    }

    /**
     * Get conversation second user.
     *
     * @return collection
     */
    public function userTwo()
    {
        return $this->belongsTo(config('messenger.user.model', 'App\User'),  'user_two');
    }

    /**
     * Get conversation last message for threads.
     *
     * @return collection
     */
    public function lastMessage()
    {
        return $this->messages->last();
    }
}
