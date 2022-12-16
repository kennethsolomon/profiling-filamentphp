<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'files',
        'remarks',
        'date'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
