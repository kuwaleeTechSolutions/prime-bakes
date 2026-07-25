<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'company_name',
        'role_id', 'biller_id', 'warehouse_id', 'is_active', 'is_deleted',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_deleted' => 'boolean',
    ];

    // No email_verified_at column in this schema — the field is intentionally omitted.

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function biller()
    {
        return $this->belongsTo(Biller::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    // role_has_permissions is a plain pivot (permission_id, role_id) — no package
    // required, this mirrors it directly.
    public function hasPermission(string $permission): bool
    {
        if ($this->role?->name === 'Admin') {
            return true; // Admin role bypasses granular checks, matching role id 1 in the seed data
        }

        return $this->role
            ?->permissions()
            ->where('name', $permission)
            ->exists() ?? false;
    }

    public function isOwnerLevel(): bool
    {
        // Owner/manager accounts in the source data have no warehouse_id/biller_id —
        // they're scoped to the whole business, not one branch.
        return is_null($this->warehouse_id) && is_null($this->biller_id);
    }
}
