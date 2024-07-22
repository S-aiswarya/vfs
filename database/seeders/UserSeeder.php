<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => 'Super Admin',
            'email' => 'sobha@spiderworks.in',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'name' => 'Admin',
            'guard_name' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\Models\Admin',
            'model_id' => 1
        ]);

        $permissions = [
            ['id' => 1, 'name' => 'user_listing', 'route' => 'admin.users.index', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'name' => 'user_adding', 'route' => 'admin.users.create', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'name' => 'user_editing', 'route' => 'admin.users.edit', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'name' => 'user_deleting', 'route' => 'admin.users.deleting', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'name' => 'role_adding', 'route' => 'admin.roles.create', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'name' => 'role_editing', 'route' => 'admin.roles.edit', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 7, 'name' => 'role_listing', 'route' => 'admin.roles.index', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 8, 'name' => 'role_deleting', 'route' => 'admin.roles.destroy', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 9, 'name' => 'permission_listing', 'route' => 'admin.permissions.index', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 10, 'name' => 'permission_adding', 'route' => 'admin.permissions.create', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 11, 'name' => 'permission_editing', 'route' => 'admin.permissions.edit', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 12, 'name' => 'permission_deleting', 'route' => 'admin.permissions.deleting', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 13, 'name' => 'admin_links_listing', 'route' => 'admin.admin-links.index', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 14, 'name' => 'admin_links_adding', 'route' => 'admin.admin-links.create', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 15, 'name' => 'admin_links_editing', 'route' => 'admin.admin-links.edit', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 16, 'name' => 'admin_links_deleting', 'route' => 'admin.admin-links.deleting', 'guard_name' => 'admin', 'public' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 41, 'name' => 'site_settings', 'route' => 'admin.settings.index', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 76, 'name' => 'login_history_listing', 'route' => 'admin.login-history.index', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 77, 'name' => 'login_history_deleting', 'route' => 'admin.login-history.destroy', 'guard_name' => 'admin', 'public' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('permissions')->insert($permissions);

        $role_has_permissions = [
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 9, 'role_id' => 1],
            ['permission_id' => 10, 'role_id' => 1],
            ['permission_id' => 11, 'role_id' => 1],
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 13, 'role_id' => 1],
            ['permission_id' => 14, 'role_id' => 1],
            ['permission_id' => 15, 'role_id' => 1],
            ['permission_id' => 16, 'role_id' => 1],
            ['permission_id' => 41, 'role_id' => 1],
            ['permission_id' => 76, 'role_id' => 1],
            ['permission_id' => 77, 'role_id' => 1],
        ];

        DB::table('role_has_permissions')->insert($role_has_permissions);

        $admin_links = [
            ['id' => 14, 'permissions_id' => 1, 'name' => 'Users', 'parent_id'=> 0, 'icon' => '<i class=\"fas fa-users\"></i>', 'display_order' => 6, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 15, 'permissions_id' => 7, 'name' => 'Roles', 'parent_id'=> 0, 'icon' => '<i class=\"fab fa-black-tie\"></i>', 'display_order' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 16, 'permissions_id' => 9, 'name' => 'Settings', 'parent_id'=> 0, 'icon' => '<i class=\"fas fa-cogs\"></i>', 'display_order' => 10, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 21, 'permissions_id' => 9, 'name' => 'Permissions', 'parent_id'=> 16, 'icon' => '<i class=\"fas fa-arrows-alt\"></i>', 'display_order' => 14, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 22, 'permissions_id' => 13, 'name' => 'Admin Links', 'parent_id'=> 16, 'icon' => '<i class=\"fas fa-bars\"></i>', 'display_order' => 13, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 40, 'permissions_id' => 76, 'name' => 'Login History', 'parent_id'=> 0, 'icon' => '<i class=\"fas fa-sign-in-alt\"></i>', 'display_order' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 29, 'permissions_id' => 41, 'name' => 'Site Settings', 'parent_id'=> 0, 'icon' => '<i class=\"fas fa-toolbox\"></i>', 'display_order' => 5, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('admin_links')->insert($admin_links);

        $settings = [
            ['id' => 1, 'code' => 'logo', 'input_type' => 'File', 'value_text' => 'uploads/settings/logo.png', 'settings_type' => 'Logo', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'code' => 'logo_small', 'input_type' => 'File', 'value_text' => 'uploads/settings/logo-sm612c5acedd245.png', 'settings_type' => 'Logo', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'code' => 'fav_icon', 'input_type' => 'File', 'value_text' => 'uploads/settings/favicon.ico', 'settings_type' => 'Logo', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'code' => 'contact_address1', 'input_type' => 'Text', 'value_text' => 'Address1', 'settings_type' => 'Contact', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 10, 'code' => 'contact_number', 'input_type' => 'Text', 'value_text' => '9496849448', 'settings_type' => 'Contact', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 11, 'code' => 'whatsapp_number', 'input_type' => 'Text', 'value_text' => '9496849448', 'settings_type' => 'Contact', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 12, 'code' => 'contact_email', 'input_type' => 'Text', 'value_text' => 'contact@spiderworks.in', 'settings_type' => 'Contact', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 13, 'code' => 'twitter-link', 'input_type' => 'Text', 'value_text' => 'https://twitter.com', 'settings_type' => 'Social Media', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 14, 'code' => 'facebook-link', 'input_type' => 'Text', 'value_text' => 'https://facebook.com', 'settings_type' => 'Social Media', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 15, 'code' => 'intagram-link', 'input_type' => 'Text', 'value_text' => 'https://instagram.com', 'settings_type' => 'Social Media', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 16, 'code' => 'linkedin-link', 'input_type' => 'Text', 'value_text' => 'https://in.linkedin.com/', 'settings_type' => 'Social Media', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 17, 'code' => 'youtube-link', 'input_type' => 'Text', 'value_text' => 'https://youtube.com', 'settings_type' => 'Social Media', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 21, 'code' => 'site_name', 'input_type' => 'Text', 'value_text' => 'SpiderWorks', 'settings_type' => 'Common', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 25, 'code' => 'smtp_host', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 26, 'code' => 'smtp_port', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 27, 'code' => 'smtp_user', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 28, 'code' => 'smtp_password', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 29, 'code' => 'smtp_encryption', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 30, 'code' => 'smtp_from_address', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 31, 'code' => 'smtp_from_name', 'input_type' => 'Text', 'value_text' => NULL, 'settings_type' => 'Smtp', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 32, 'code' => 'google_map_embed_code', 'input_type' => 'Text', 'value_text' => '', 'settings_type' => 'Contact', 'created_by'=> 1, 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        DB::table('settings')->insert($settings);

    }
}
