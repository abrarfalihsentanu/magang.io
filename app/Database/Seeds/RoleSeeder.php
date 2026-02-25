<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_role' => 'Super Admin',
                'kode_role' => 'admin',
                'deskripsi' => 'Full access to all system features',
                'permissions' => json_encode([
                    'dashboard' => ['view'],
                    'users' => ['create', 'read', 'update', 'delete'],
                    'roles' => ['create', 'read', 'update', 'delete'],
                    'divisi' => ['create', 'read', 'update', 'delete'],
                    'interns' => ['create', 'read', 'update', 'delete'],
                    'attendance' => ['create', 'read', 'update', 'delete', 'approve'],
                    'activities' => ['create', 'read', 'update', 'delete', 'approve'],
                    'projects' => ['create', 'read', 'update', 'delete', 'assess'],
                    'kpi' => ['create', 'read', 'update', 'delete', 'assess'],
                    'allowance' => ['create', 'read', 'update', 'delete', 'process'],
                    'reports' => ['view', 'export'],
                    'archive' => ['view', 'create'],
                    'settings' => ['read', 'update'],
                    'audit_logs' => ['view']
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_role' => 'HR Staff',
                'kode_role' => 'hr',
                'deskripsi' => 'Human Capital staff - manage interns and approvals',
                'permissions' => json_encode([
                    'dashboard' => ['view'],
                    'users' => ['create', 'read', 'update'],
                    'divisi' => ['read'],
                    'interns' => ['create', 'read', 'update', 'delete'],
                    'attendance' => ['read', 'approve'],
                    'activities' => ['read', 'approve'],
                    'projects' => ['read'],
                    'kpi' => ['read', 'assess'],
                    'allowance' => ['create', 'read', 'update'],
                    'reports' => ['view', 'export'],
                    'archive' => ['view', 'create']
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_role' => 'Finance',
                'kode_role' => 'finance',
                'deskripsi' => 'Finance staff - process allowance payments',
                'permissions' => json_encode([
                    'dashboard' => ['view'],
                    'allowance' => ['read', 'process'],
                    'reports' => ['view', 'export']
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_role' => 'Mentor/Pembimbing',
                'kode_role' => 'mentor',
                'deskripsi' => 'Mentor - guide and assess interns',
                'permissions' => json_encode([
                    'dashboard' => ['view'],
                    'interns' => ['read'], // only their mentees
                    'attendance' => ['read', 'approve'],
                    'activities' => ['read', 'approve'],
                    'projects' => ['read', 'assess'],
                    'kpi' => ['read', 'assess'],
                    'reports' => ['view']
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_role' => 'Pemagang',
                'kode_role' => 'intern',
                'deskripsi' => 'Intern - access own data only',
                'permissions' => json_encode([
                    'dashboard' => ['view'],
                    'attendance' => ['create', 'read'], // own only
                    'activities' => ['create', 'read', 'update'], // own only
                    'projects' => ['create', 'read', 'update'], // own only
                    'kpi' => ['read'], // own only
                    'allowance' => ['read'] // own only
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('roles')->insertBatch($data);

        echo "✅ 5 Roles berhasil di-seed!\n";
    }
}
