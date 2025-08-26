<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'line1',
        'line2',
        'city',
        'state',
        'country',
        'postal_code',
        'label',
        'is_primary',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
