<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Employee extends Model
{
    use HasFactory;
   protected $fillable = [
        'department_id','first_name','last_name','email','date_of_birth','designation'
    ];

    protected $dates = ['date_of_birth'];

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }

    public function phoneNumbers(): HasMany {
        return $this->hasMany(EmployeePhoneNumber::class);
    }

    public function addresses(): HasMany {
        return $this->hasMany(EmployeeAddress::class);
    }
}
