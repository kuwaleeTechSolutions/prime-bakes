<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['date', 'to_date', 'employee_id', 'user_id', 'checkin', 'checkout', 'status', 'note'];

    protected $casts = ['date' => 'date', 'to_date' => 'date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
