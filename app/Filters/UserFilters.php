<?php

namespace App\Filters;

class UserFilters extends Filters
{
    /**
     * List of available filters for User model.
     */
    public $modelFilters = ['role', 'roles'];

    /**
     * Filters users by role.
     */
    protected function role(string $role)
    {
        $this->builder->whereHas('role', function ($query) use ($role) {
            return $query->where('name', $role);
        });
    }

    /**
     * Filters users by [multiple] roles.
     */
    protected function roles(array $roles)
    {
        $this->builder->whereIn('role_id', $roles);
    }
}
