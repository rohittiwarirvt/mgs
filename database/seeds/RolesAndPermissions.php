<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->truncate();
        $roles = [
            ['Master Admin' , 'Master Admin', ' Master Admin',1],
            ['Individual Customer', 'Customer', 'Individual Customer',4],
            ['Individual Service Provider', 'Individual Service Provider', 'Individual Service Provider',6],
            ['CSR','CSR', 'CSR/Tele caller',11],
            ['Assigner', 'Assigner', 'Assigner',12],
            ['Accounts Assistance', 'Accounts Assistance', 'Accounts Assistance',13],
            ['Supervisor', 'Supervisor', 'Supervisor',14]
        ];

        foreach ($roles as $key => $value) {
          DB::table('roles')->insert([
                    'id' => $roles[$key][3],
                    'name' => $roles[$key][0],
                    'display_name' =>  $roles[$key][1],
                    'description' =>  $roles[$key][2],
                    'created_at' => new DateTime,
                    'updated_at' => new DateTime,
                    'is_builtin' => 1
            ]);
        }
        DB::table('permissions')->truncate();

        $permissions = [
            ['list-role' , 'List Role', ' List Roles', 1],
            ['view-permission', 'View Permission', 'View Permissions', 2],
            ['list-permission', 'List Permission', 'List Permissions', 3],
            ['add-role', 'Add Role', 'Add Roles', 4],
            ['edit-role', 'Edit Role', 'Edit Roles', 5],
            ['add-permission', 'Add Permission', 'Add Permissions', 6],
            ['edit-permission', 'Edit Permission', 'Edit Permissions', 7],
            ['view-site', 'View-Site', 'View-Site'],
            ['view-published',  'View-Published', 'View-Published'],
            ['book-service',  'Book-Service', 'Book-Service'],
        ];

        foreach ($permissions as $key => $value) {
            $id = isset($permissions[$key][3]) ? $permissions[$key][3] : "";
            $data = [
                    'id' => $id,
                    'name' => $permissions[$key][0],
                    'display_name' =>  $permissions[$key][1],
                    'description' =>  $permissions[$key][2],
                    'created_at' => new DateTime,
                    'updated_at' => new DateTime,
                    'is_builtin' => 1
            ];

          DB::table('permissions')->insert(array_filter($data));
        }

        $user_id = DB::table('users')->where('username', 'mgsadmin@mgs.com')->first();

        if(!$user_id){
            DB::table('users')->truncate();

            $users = array(
                ['username' => 'guestuser@mgs.com', 'email' => 'guest@mgs.com', 'password' => Hash::make('admin@1234'),'status' => 1],
                ['username' => 'mgsadmin@mgs.com', 'email' => 'admin@mgs.com', 'password' => Hash::make('admin@1234'), 'status' => 1],
                ['username' => 'apiuser@mgs.com', 'email' => 'apiuser@mgs.com', 'password' => Hash::make('admin@1234'), 'status' => 1],
            );

            // Loop through each user above and create the record for them in the database
            foreach ($users as $user){
                User::create($user);
            }

            $user_id = DB::table('users')->where('username', 'mgsadmin@mgs.com')->first();

            //Adding Roles for Admin User
            DB::table('role_user')->truncate();
                $user_roles = array(
                ['user_id' => $user_id->id, 'role_id' => 1],
            );

            DB::table('role_user')->insert($user_roles);

            //Adding Permisssions for Admin User
            //Adding Roles for Admin User
            DB::table('permission_role')->truncate();
                $user_permissions = array(
                ['permission_id' => 1, 'role_id' => 1],
            );

            DB::table('permission_role')->insert($user_permissions);
        }
    }
}
