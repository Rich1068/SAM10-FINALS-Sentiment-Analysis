<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{

    use HasFactory;
    protected $fillable = [
        'user_id',
        'input_text',
        'file_path',
        'negative_score',
        'neutral_score',
        'positive_score',
        'result',
    ];
}
