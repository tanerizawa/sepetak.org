        // 3. Assign permissions per role
        $rolePermissionMap = [
            'superadmin' => Permission::all()->pluck('name')->toArray(),
            'admin'      => Permission::all()->pluck('name')->toArray(),
            'operator'   => ['manage-members', 'manage-cases', 'manage-advocacy', 'manage-events', 'manage-content'],
            'viewer'     => [],
        ];
        foreach ($rolePermissionMap as $roleName => $permNames) {
            try {
                Role::findByName($roleName, 'web')->syncPermissions($permNames);
            } catch (\Exception $e) {
                $this->command->warn("Could not sync permissions for '{$roleName}': " . $e->getMessage());
            }
        }

        // 4. Default users per role (only superadmin seeded by default; others only if missing)
        $defaultUsers = [
            ['email' => 'admin@sepetak.org',    'name' => 'Administrator SEPETAK', 'role' => 'superadmin'],
            ['email' => 'redaksi@sepetak.org',  'name' => 'Redaksi SEPETAK',       'role' => 'operator'],
            ['email' => 'publik@sepetak.org',   'name' => 'Akun Viewer SEPETAK',   'role' => 'viewer'],
        ];
        foreach ($defaultUsers as $u) {
            try {
                $user = User::updateOrCreate(
                    ['email' => $u['email']],
                    [
                        'name'      => $u['name'],
                        'password'  => Hash::make('password'),
                        'is_active' => true,
                    ]
                );
                $user->syncRoles([$u['role']]);
            } catch (\Exception $e) {
                $this->command->warn("User '{$u['email']}' skipped: " . $e->getMessage());
            }
        }