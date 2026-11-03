<?php

namespace Database\Seeders;

use App\Core\Database;
use App\Models\User;

class RoleAndPermissionSeeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $permConfig = config('permission.default_permissions', []);
        $roleConfig = config('permission.default_roles', []);

        $permIdMap = [];
        foreach ($permConfig as $name => $meta) {
            $id = Database::insert('permissions', [
                'name' => $name,
                'guard_name' => 'web',
                'group_name' => $meta['group'] ?? 'general',
                'description' => $meta['description'] ?? '',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $permIdMap[$name] = $id;
        }

        $roleIdMap = [];
        foreach ($roleConfig as $roleName => $meta) {
            $roleId = Database::insert('roles', [
                'name' => $roleName,
                'guard_name' => 'web',
                'badge_color' => $meta['badge_color'] ?? '#c6994f',
                'description' => $meta['description'] ?? '',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $roleIdMap[$roleName] = $roleId;

            foreach (($meta['permissions'] ?? []) as $permName) {
                if (isset($permIdMap[$permName])) {
                    Database::insert('role_has_permissions', [
                        'permission_id' => $permIdMap[$permName],
                        'role_id' => $roleId,
                    ]);
                }
            }
        }

        $demoTokenHash = password_hash((string)env('DEMO_ACCESS_TOKEN', 'mosaic-concierge-vip'), PASSWORD_BCRYPT);

        $users = [
            [
                'name' => 'Alessandro Visconti',
                'email' => 'admin@mosaic.digital',
                'phone' => '+39 02 8900 4101',
                'company_name' => 'Mosaic Holding S.r.l.',
                'vip_tier' => 'Founder',
                'avatar_initials' => 'AV',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Beatrice Montefeltro',
                'email' => 'concierge@mosaic.digital',
                'phone' => '+39 02 8900 4120',
                'company_name' => 'Mosaic Private Desk',
                'vip_tier' => 'Senior Desk',
                'avatar_initials' => 'BM',
                'role' => 'Concierge Manager',
            ],
            [
                'name' => 'Lorenzo De Mari',
                'email' => 'fornitore@mosaic.digital',
                'phone' => '+39 0789 94110',
                'company_name' => 'Smeralda Estates & Fleet',
                'vip_tier' => 'Gold Partner',
                'avatar_initials' => 'LD',
                'role' => 'Fornitore/Partner',
            ],
            [
                'name' => 'Edoardo Caracciolo',
                'email' => 'riviera@mosaic.digital',
                'phone' => '+39 089 871450',
                'company_name' => 'Riviera Dimore & Yachting',
                'vip_tier' => 'Gold Partner',
                'avatar_initials' => 'EC',
                'role' => 'Fornitore/Partner',
            ],
            [
                'name' => 'Ginevra Borromeo',
                'email' => 'vip@mosaic.digital',
                'phone' => '+41 91 922 8810',
                'company_name' => 'Family Office Lugano',
                'vip_tier' => 'Black Member',
                'avatar_initials' => 'GB',
                'role' => 'Cliente VIP',
            ],
        ];

        foreach ($users as $u) {
            $roleName = $u['role'];
            unset($u['role']);
            $u['password'] = $demoTokenHash;
            $u['is_active'] = 1;
            $u['created_at'] = $now;
            $u['updated_at'] = $now;
            $userId = Database::insert('users', $u);
            $userObj = User::find($userId);
            if ($userObj) {
                $userObj->assignRole($roleName);
            }
        }

        $settings = [
            [
                'key' => 'supplier_portal_mode',
                'value' => 'standby',
                'group_name' => 'portal',
                'description' => 'Stato Portale Fornitore: standby (In Standby / Accesso Beta) oppure active (Operativo)',
            ],
            [
                'key' => 'concierge_phone',
                'value' => '+39 02 8900 4120',
                'group_name' => 'contact',
                'description' => 'Linea diretta Private Concierge Desk 24/7',
            ],
            [
                'key' => 'concierge_email',
                'value' => 'concierge@mosaic.digital',
                'group_name' => 'contact',
                'description' => 'Indirizzo email Private Concierge Desk',
            ],
            [
                'key' => 'ai_orchestrator_model',
                'value' => 'gemini-2.5-flash',
                'group_name' => 'ai',
                'description' => 'Modello AI per orchestrazione itinerari multi-categoria',
            ],
        ];

        foreach ($settings as $s) {
            $s['created_at'] = $now;
            $s['updated_at'] = $now;
            Database::insert('settings', $s);
        }
    }
}
