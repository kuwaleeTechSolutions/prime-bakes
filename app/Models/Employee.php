<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name', 'email', 'phone_number', 'department_id', 'user_id',
        'image', 'address', 'city', 'country', 'is_active', 'salary_amount',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'salary_amount' => 'decimal:2',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Whether this employee has already been paid for a given month string
    // (e.g. "July") — used to avoid accidental double-payment.
    public function paidFor(string $month): bool
    {
        return $this->payrolls()->where('month', $month)->exists();
    }
}
