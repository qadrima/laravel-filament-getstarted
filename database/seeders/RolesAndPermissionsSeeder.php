<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get Filament resources and generate permissions automatically
        $permissions = $this->getResourcePermissions();

        // Create permissions
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $developerRole = Role::firstOrCreate(
            ['name' => 'developer'],
            ['guard_name' => 'web']
        );
        $developerRole->syncPermissions(Permission::all());
            
        // Create a user and assign the developer role
        $developerUser = User::firstOrCreate(
            ['email' => 'qadri.dev@gmail.com'], // search criteria
            [
                'name' => 'Qadri',
                'password' => bcrypt('password'),
            ] // if not found, create with these attributes
        );
        $developerUser->assignRole('developer');
    }
    
    /**
     * Get permissions based on Filament resources
     */
    private function getResourcePermissions(): array
    {
        $permissions = [];
        $resourcesPath = app_path('Filament/Admin/Resources');
        
        if (File::exists($resourcesPath)) {
            $resourceFiles = File::files($resourcesPath);
            
            foreach ($resourceFiles as $file) {
                if (Str::endsWith($file->getFilename(), 'Resource.php')) {
                    // Extract resource name (e.g., "User" from "UserResource.php")
                    $resourceName = str_replace('Resource.php', '', $file->getFilename());
                    $resourceName = Str::lower($resourceName);
                    
                    // Add standard CRUD permissions for each resource
                    $permissions[] = "view {$resourceName}s";
                    $permissions[] = "create {$resourceName}s";
                    $permissions[] = "edit {$resourceName}s";
                    $permissions[] = "delete {$resourceName}s";
                }
            }
        }
        
        // Ensure we have all the original permissions
        $standardPermissions = [
            // Add any additional permissions that might not be covered by resources
        ];
        
        return array_unique(array_merge($permissions, $standardPermissions));
    }
}