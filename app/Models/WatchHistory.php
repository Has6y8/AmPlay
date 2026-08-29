<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model {
    protected $fillable = ['user_id', 'anime_id', 'episode_id', 'anime_title', 'episode_number', 'poster_url'];
}