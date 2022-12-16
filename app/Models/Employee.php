<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Employee extends Model
{
  use HasFactory;

  protected $fillable = [
    'department_id',
    'employee_number',
    'first_name',
    'middle_name',
    'last_name',
    'address',
    'birth_date',
    'phone_number',
    'zip_code',
    'date_hired',
    'is_active',
  ];
  public static function boot()
  {
    parent::boot();

    self::created(function ($model) {
      $employee_id = $model->id;

      $attachment_list = Config::get('constants.attachments');

      foreach ($attachment_list as $key => $attachment) {
        Attachment::create([
          'employee_id' => $employee_id,
          'name' => $attachment['name'],
          'attachment' => ''
        ]);
      }
    });
  }

  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  public function attachments()
  {
    return $this->hasMany(Attachment::class);
  }
}
