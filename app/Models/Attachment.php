<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $casts = [
        'attachment' => 'array',
    ];

    protected $fillable = [
        'employee_id',
        'name',
        'attachment',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
