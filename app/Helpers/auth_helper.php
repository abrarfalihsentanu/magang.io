<?php

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     */
    function is_logged_in(): bool
    {
        return session()->has('logged_in') && session()->get('logged_in') === true;
    }
}

if (!function_exists('user_role')) {
    /**
     * Get current user role code
     */
    function user_role(): ?string
    {
        return session()->get('role_code');
    }
}

if (!function_exists('user_id')) {
    /**
     * Get current user ID
     */
    function user_id(): ?int
    {
        return session()->get('user_id');
    }
}

if (!function_exists('user_name')) {
    /**
     * Get current user name
     */
    function user_name(): ?string
    {
        return session()->get('nama_lengkap');
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin
     */
    function is_admin(): bool
    {
        return user_role() === 'admin';
    }
}

if (!function_exists('is_hr')) {
    /**
     * Check if current user is HR
     */
    function is_hr(): bool
    {
        return user_role() === 'hr';
    }
}

if (!function_exists('is_mentor')) {
    /**
     * Check if current user is mentor
     */
    function is_mentor(): bool
    {
        return user_role() === 'mentor';
    }
}

if (!function_exists('is_finance')) {
    /**
     * Check if current user is finance
     */
    function is_finance(): bool
    {
        return user_role() === 'finance';
    }
}

if (!function_exists('is_intern')) {
    /**
     * Check if current user is intern
     */
    function is_intern(): bool
    {
        return user_role() === 'intern';
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if user has specific permission
     * 
     * @param string $module Module name (e.g. 'users', 'attendance')
     * @param string $action Action name (e.g. 'create', 'read', 'update', 'delete')
     * @return bool
     */
    function has_permission(string $module, string $action): bool
    {
        $permissions = session()->get('permissions');

        if (!$permissions || !is_array($permissions)) {
            return false;
        }

        if (!isset($permissions[$module])) {
            return false;
        }

        return in_array($action, $permissions[$module]);
    }
}

if (!function_exists('can_access')) {
    /**
     * Check if user can access specific module
     * 
     * @param string $module Module name
     * @return bool
     */
    function can_access(string $module): bool
    {
        $permissions = session()->get('permissions');

        if (!$permissions || !is_array($permissions)) {
            return false;
        }

        return isset($permissions[$module]) && !empty($permissions[$module]);
    }
}

if (!function_exists('user_info')) {
    /**
     * Get all user session info
     * 
     * @return array
     */
    function user_info(): array
    {
        return [
            'id' => session()->get('user_id'),
            'nik' => session()->get('nik'),
            'name' => session()->get('nama_lengkap'),
            'email' => session()->get('email'),
            'foto' => session()->get('foto'),
            'role_id' => session()->get('id_role'),
            'role_name' => session()->get('role_name'),
            'role_code' => session()->get('role_code'),
            'divisi' => session()->get('nama_divisi'),
            'permissions' => session()->get('permissions')
        ];
    }
}
