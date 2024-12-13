<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WebadminController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AdminLinkController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Auth\AuthenticateSessionOtpController;
use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\Admin\DocumentTemplateController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LeadSourceController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\ReferralTypeController;
use App\Http\Controllers\Admin\StageController;
use App\Http\Controllers\Admin\StageEmailActionController;
use App\Http\Controllers\Admin\StageTaskActionController;
use App\Http\Controllers\Admin\SubStageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\GateController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\EmployController;
use App\Http\Controllers\Admin\GuardController;
use App\Http\Controllers\Admin\KeyTypeController;
use App\Http\Controllers\Admin\RegistertypeController;
use Illuminate\Support\Facades\Route;

$prefix = (config()->has('admin.url_prefix'))?config()->get('admin.url_prefix'):'admin';
$middleware = (config()->has('admin.admin_middleware'))?config()->get('admin.admin_middleware'):'auth';

Route::group(['prefix' => $prefix, 'middleware' => ['web']], function () use($middleware) {

    
    Route::post('/request-otp', [AuthenticateSessionOtpController::class, 'request_otp'])->name('admin.auth.request-otp');
    Route::post('/validate-otp', [AuthenticateSessionOtpController::class, 'validate_otp'])->name('admin.auth.validate-otp');
    Route::get('/resend-otp/{id}', [AuthenticateSessionOtpController::class, 'resend_otp'])->name('admin.auth.resend-otp');
    Route::post('/logout', [AuthenticateSessionOtpController::class, 'logout'])->name('admin.auth.logout');

    Route::get('validation/unique-slug', [WebadminController::class, 'unique_slug'])->name('admin.unique-slug');

	Route::group(['middleware' => $middleware], function(){
		Route::get('/dashboard', [WebadminController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/validation/roles', [WebadminController::class, 'unique_roles'])->name('admin.validation.roles');
        Route::get('/validation/users', [WebadminController::class, 'unique_users'])->name('admin.validation.users');
      
        Route::get('/select2/countries', [WebadminController::class, 'select2_countries'])->name('admin.select2.countries');
        Route::get('/select2/cities', [WebadminController::class, 'select2_cities'])->name('admin.select2.cities');
        Route::get('/select2/locations', [WebadminController::class, 'select2_locations'])->name('admin.select2.locations');
        Route::get('/select2/centers', [WebadminController::class, 'select2_centers'])->name('admin.select2.centers');
        Route::get('/select2/gates', [WebadminController::class, 'select2_gates'])->name('admin.select2.gates');
        Route::get('/select2/key_types', [WebadminController::class, 'select2_key_types'])->name('admin.select2.key_types');
        Route::get('/select2/register_types', [WebadminController::class, 'select2_register_types'])->name('admin.select2.register_types');
        Route::get('/select2/check_in_type', [WebadminController::class, 'select2_check_in_type'])->name('admin.select2.check_in_type');
        Route::get('/select2/stages/{type?}', [WebadminController::class, 'select2_stages'])->name('admin.select2.stages');
        Route::get('/select2/users/{role?}', [WebadminController::class, 'select2_users'])->name('admin.select2.users');
        Route::get('/select2/branches', [WebadminController::class, 'select2_branches'])->name('admin.select2.branches');
        Route::get('/select2/global-countries', [WebadminController::class, 'select2_global_countries'])->name('admin.select2.global-countries');
        Route::get('/select2/email-templates', [WebadminController::class, 'select2_email_templates'])->name('admin.select2.email-templates');
        Route::get('/select2/user-roles', [WebadminController::class, 'select2_user_roles'])->name('admin.select2.user-roles');
        Route::get('/select2/agencies', [WebadminController::class, 'select2_agencies'])->name('admin.select2.agencies');
        
        //users
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::get('/users/show/{id}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/destroy/{id}', [UserController::class, 'destroy'] )->name('admin.users.destroy');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users/update', [UserController::class, 'update'])->name('admin.users.update');
        Route::get('/users/change-status/{id}', [UserController::class, 'changeStatus'])->name('admin.users.change-status');
        Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/targets/{id}/{intake?}', [UserController::class, 'targets'])->name('admin.users.targets');
        Route::post('/users/targets-store', [UserController::class, 'targetStore'])->name('admin.users.targets-store');
        Route::get('/users/modify-status/{id}', [UserController::class, 'modifyStatus'])->name('admin.users.modify-status');
        Route::post('/users/modify-status-store', [UserController::class, 'updateModifiedStatus'])->name('admin.users.modify-status-store');
        Route::get('/users/access-unallocated-leads/{id}', [UserController::class, 'accessUnallocatedLeads'])->name('admin.users.access-unallocated-leads');

        //user roles
        Route::get('/users/roles/edit/{id}', [UserRoleController::class, 'edit'])->name('admin.users.roles.edit');
        Route::get('/users/roles/show/{id}', [UserRoleController::class, 'show'])->name('admin.users.roles.show');
        Route::get('/users/roles/destroy/{id}', [UserRoleController::class, 'destroy'] )->name('admin.users.roles.destroy');
        Route::get('/users/roles/create', [UserRoleController::class, 'create'])->name('admin.users.roles.create');
        Route::post('/users/roles/update', [UserRoleController::class, 'update'])->name('admin.users.roles.update');
        Route::get('/users/roles/change-status/{id}', [UserRoleController::class, 'changeStatus'])->name('admin.users.roles.change-status');
        Route::post('/users/roles/store', [UserRoleController::class, 'store'])->name('admin.users.roles.store');
        Route::get('/users/roles', [UserRoleController::class, 'index'])->name('admin.users.roles.index');

        //permissions
        Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
        Route::get('/permissions/show/{id}', [PermissionController::class, 'show'])->name('admin.permissions.show');
        Route::get('/permissions/destroy/{id}', [PermissionController::class, 'destroy'] )->name('admin.permissions.destroy');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
        Route::post('/permissions/update', [PermissionController::class, 'update'])->name('admin.permissions.update');
        Route::post('/permissions/store', [PermissionController::class, 'store'])->name('admin.permissions.store');
        Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
        Route::get('/permissions/change-status/{id}', function(){
            echo "Permission Denied"; exit;
        })->name('admin.permissions.change-status');

        //admin roles
        Route::get('/admins/roles/edit/{id}', [AdminRoleController::class, 'edit'])->name('admin.admins.roles.edit');
        Route::get('/admins/roles/show/{id}', [AdminRoleController::class, 'show'])->name('admin.admins.roles.show');
        Route::get('/admins/roles/destroy/{id}', [AdminRoleController::class, 'destroy'] )->name('admin.admins.roles.destroy');
        Route::get('/admins/roles/create', [AdminRoleController::class, 'create'])->name('admin.admins.roles.create');
        Route::post('/admins/roles/update', [AdminRoleController::class, 'update'])->name('admin.admins.roles.update');
        Route::get('/admins/roles/change-status/{id}', [AdminRoleController::class, 'changeStatus'])->name('admin.admins.roles.change-status');
        Route::post('/admins/roles/store', [AdminRoleController::class, 'store'])->name('admin.admins.roles.store');
        Route::get('/admins/roles', [AdminRoleController::class, 'index'])->name('admin.admins.roles.index');

        //admin links
        Route::get('/admin-links/edit/{id}', [AdminLinkController::class, 'edit'])->name('admin.admin-links.edit');
        Route::get('/admin-links/destroy/{id}', [AdminLinkController::class, 'destroy'] )->name('admin.admin-links.destroy');
        Route::get('/admin-links/create', [AdminLinkController::class, 'create'])->name('admin.admin-links.create');
        Route::post('/admin-links/update', [AdminLinkController::class, 'update'])->name('admin.admin-links.update');
        Route::post('/admin-links/store', [AdminLinkController::class, 'store'])->name('admin.admin-links.store');
        Route::get('/admin-links/{id?}', [AdminLinkController::class, 'index'])->name('admin.admin-links.index');
        Route::get('/admin-links/change-status/{id}', function(){
            echo "Permission Denied"; exit;
        })->name('admin.admin-links.change-status');
        Route::post('/admin-links/order-store', [AdminLinkController::class, 'order_store'])->name('admin.admin-links.order-store');

        //login history
        Route::get('login-history', [LoginHistoryController::class, 'index'])->name('admin.login-history.index');
        Route::get('/login-history/create', [LoginHistoryController::class, 'create'])->name('admin.login-history.create');
        Route::get('/login-history/edit/{id}', [LoginHistoryController::class, 'edit'])->name('admin.login-history.edit');
        Route::get('login-history/destroy/{id}', [LoginHistoryController::class, 'destroy'])->name('admin.login-history.destroy');
        Route::post('/login-history/update', [LoginHistoryController::class, 'update'])->name('admin.login-history.update');
        Route::post('/login-history/store', [LoginHistoryController::class, 'store'])->name('admin.login-history.store');
        Route::get('/login-history/change-status/{id}', [LoginHistoryController::class, 'changeStatus'])->name('admin.login-history.change-status');
        
       
        //settings
        Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings/store', [SettingController::class, 'store'])->name('admin.settings.store');

        //admins
        Route::get('/admins/edit/{id}', [AdminController::class, 'edit'])->name('admin.admins.edit');
        Route::get('/admins/show/{id}', [AdminController::class, 'show'])->name('admin.admins.show');
        Route::get('/admins/destroy/{id}', [AdminController::class, 'destroy'] )->name('admin.admins.destroy');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admin.admins.create');
        Route::post('/admins/update', [AdminController::class, 'update'])->name('admin.admins.update');
        Route::get('/admins/change-status/{id}', [AdminController::class, 'changeStatus'])->name('admin.admins.change-status');
        Route::post('/admins/store', [AdminController::class, 'store'])->name('admin.admins.store');
        Route::get('/admins', [AdminController::class, 'index'])->name('admin.admins.index');

        //lead sources
        Route::get('/lead-sources', [LeadSourceController::class, 'index'])->name('admin.lead-sources.index');
        Route::get('/lead-sources/create', [LeadSourceController::class, 'create'])->name('admin.lead-sources.create');
        Route::get('/lead-sources/edit/{id}', [LeadSourceController::class, 'edit'])->name('admin.lead-sources.edit');
        Route::get('/lead-sources/destroy/{id}', [LeadSourceController::class, 'destroy'] )->name('admin.lead-sources.destroy');
        Route::get('/lead-sources/show/{id}', [LeadSourceController::class, 'show'])->name('admin.lead-sources.show');
        Route::post('/lead-sources/update', [LeadSourceController::class, 'update'])->name('admin.lead-sources.update');
        Route::get('/lead-sources/change-status/{id}', [LeadSourceController::class, 'changeStatus'])->name('admin.lead-sources.change-status');
        Route::post('/lead-sources/store', [LeadSourceController::class, 'store'])->name('admin.lead-sources.store');

        //referral types
        Route::get('/referral-types', [ReferralTypeController::class, 'index'])->name('admin.referral-types.index');
        Route::get('/referral-types/create', [ReferralTypeController::class, 'create'])->name('admin.referral-types.create');
        Route::get('/referral-types/edit/{id}', [ReferralTypeController::class, 'edit'])->name('admin.referral-types.edit');
        Route::get('/referral-types/destroy/{id}', [ReferralTypeController::class, 'destroy'] )->name('admin.referral-types.destroy');
        Route::get('/referral-types/show/{id}', [ReferralTypeController::class, 'show'])->name('admin.referral-types.show');
        Route::post('/referral-types/update', [ReferralTypeController::class, 'update'])->name('admin.referral-types.update');
        Route::get('/referral-types/change-status/{id}', [ReferralTypeController::class, 'changeStatus'])->name('admin.referral-types.change-status');
        Route::post('/referral-types/store', [ReferralTypeController::class, 'store'])->name('admin.referral-types.store');


        //offices
        Route::get('/offices', [OfficeController::class, 'index'])->name('admin.offices.index');
        Route::get('/offices/create', [OfficeController::class, 'create'])->name('admin.offices.create');
        Route::get('/offices/edit/{id}', [OfficeController::class, 'edit'])->name('admin.offices.edit');
        Route::get('/offices/destroy/{id}', [OfficeController::class, 'destroy'] )->name('admin.offices.destroy');
        Route::get('/offices/show/{id}', [OfficeController::class, 'show'])->name('admin.offices.show');
        Route::post('/offices/update', [OfficeController::class, 'update'])->name('admin.offices.update');
        Route::get('/offices/change-status/{id}', [OfficeController::class, 'changeStatus'])->name('admin.offices.change-status');
        Route::post('/offices/store', [OfficeController::class, 'store'])->name('admin.offices.store');


        //agencies
        Route::get('/agencies', [AgencyController::class, 'index'])->name('admin.agencies.index');
        Route::get('/agencies/create', [AgencyController::class, 'create'])->name('admin.agencies.create');
        Route::get('/agencies/edit/{id}', [AgencyController::class, 'edit'])->name('admin.agencies.edit');
        Route::get('/agencies/destroy/{id}', [AgencyController::class, 'destroy'] )->name('admin.agencies.destroy');
        Route::get('/agencies/show/{id}', [AgencyController::class, 'show'])->name('admin.agencies.show');
        Route::post('/agencies/update', [AgencyController::class, 'update'])->name('admin.agencies.update');
        Route::get('/agencies/change-status/{id}', [AgencyController::class, 'changeStatus'])->name('admin.agencies.change-status');
        Route::post('/agencies/store', [AgencyController::class, 'store'])->name('admin.agencies.store');
        Route::get('/agencies/emails/{id}', [AgencyController::class, 'emails'])->name('admin.agencies.emails');
        Route::post('/agencies/emails/store', [AgencyController::class, 'emailStore'])->name('admin.agencies.emails.store');
        
        //country
        Route::get('/countries', [CountryController::class, 'index'])->name('admin.countries.index');
        Route::get('/countries/create', [CountryController::class, 'create'])->name('admin.countries.create');
        Route::get('/countries/edit/{id}', [CountryController::class, 'edit'])->name('admin.countries.edit');
        Route::get('/countries/destroy/{id}', [CountryController::class, 'destroy'] )->name('admin.countries.destroy');
        Route::get('/countries/show/{id}', [CountryController::class, 'show'])->name('admin.countries.show');
        Route::post('/countries/update', [CountryController::class, 'update'])->name('admin.countries.update');
        Route::get('/countries/change-status/{id}', [CountryController::class, 'changeStatus'])->name('admin.countries.change-status');
        Route::post('/countries/store', [CountryController::class, 'store'])->name('admin.countries.store');
        Route::get('/countries/emails/{id}', [CountryController::class, 'emails'])->name('admin.countries.emails');
        Route::post('/countries/emails/store', [CountryController::class, 'emailStore'])->name('admin.countries.emails.store');  


         //cities
         Route::get('/cities', [CityController::class, 'index'])->name('admin.cities.index');
         Route::get('/cities/create', [CityController::class, 'create'])->name('admin.cities.create');
         Route::get('/cities/edit/{id}', [CityController::class, 'edit'])->name('admin.cities.edit');
         Route::get('/cities/destroy/{id}', [CityController::class, 'destroy'] )->name('admin.cities.destroy');
         Route::get('/cities/show/{id}', [CityController::class, 'show'])->name('admin.cities.show');
         Route::post('/cities/update', [CityController::class, 'update'])->name('admin.cities.update');
         Route::get('/cities/change-status/{id}', [CityController::class, 'changeStatus'])->name('admin.cities.change-status');
         Route::post('/cities/store', [CityController::class, 'store'])->name('admin.cities.store');
             
         
       //locations
      Route::get('/locations', [LocationController::class, 'index'])->name('admin.locations.index');
      Route::get('/locations/create', [LocationController::class, 'create'])->name('admin.locations.create');
      Route::get('/locations/edit/{id}', [LocationController::class, 'edit'])->name('admin.locations.edit');
      Route::get('/locations/destroy/{id}', [LocationController::class, 'destroy'] )->name('admin.locations.destroy');
      Route::get('/locations/show/{id}', [LocationController::class, 'show'])->name('admin.locations.show');
      Route::post('/locations/update', [LocationController::class, 'update'])->name('admin.locations.update');
      Route::get('/locations/change-status/{id}', [LocationController::class, 'changeStatus'])->name('admin.locations.change-status');
      Route::post('/locations/store', [LocationController::class, 'store'])->name('admin.locations.store');


     //register_types
      Route::get('/register_types', [RegistertypeController::class, 'index'])->name('admin.register_types.index');
      Route::get('/register_types/create', [RegistertypeController::class, 'create'])->name('admin.register_types.create');
      Route::get('/register_types/edit/{id}', [RegistertypeController::class, 'edit'])->name('admin.register_types.edit');
      Route::get('/register_typess/destroy/{id}', [RegistertypeController::class, 'destroy'] )->name('admin.register_types.destroy');
      Route::get('/register_types/show/{id}', [RegistertypeController::class, 'show'])->name('admin.register_types.show');
      Route::post('/register_types/update', [RegistertypeController::class, 'update'])->name('admin.register_types.update');
      Route::get('/register_types/change-status/{id}', [RegistertypeController::class, 'changeStatus'])->name('admin.register_types.change-status');
      Route::post('/register_types/store', [RegistertypeController::class, 'store'])->name('admin.register_types.store');

     //gates
     Route::get('/gates', [GateController::class, 'index'])->name('admin.gates.index');
     Route::get('/gates/create', [GateController::class, 'create'])->name('admin.gates.create');
     Route::get('/gates/edit/{id}', [GateController::class, 'edit'])->name('admin.gates.edit');
     Route::get('/gates/destroy/{id}', [GateController::class, 'destroy'] )->name('admin.gates.destroy');
     Route::get('/gates/show/{id}', [GateController::class, 'show'])->name('admin.gates.show');
     Route::post('/gates/update', [GateController::class, 'update'])->name('admin.gates.update');
     Route::get('/gates/change-status/{id}', [GateController::class, 'changeStatus'])->name('admin.gates.change-status');
     Route::post('/gates/store', [GateController::class, 'store'])->name('admin.gates.store');
     Route::get('/gates/emails/{id}', [GateController::class, 'emails'])->name('admin.gates.emails');
     Route::post('/gates/emails/store', [GateController::class, 'emailStore'])->name('admin.gates.emails.store');



       //centers
     Route::get('/centers', [CenterController::class, 'index'])->name('admin.centers.index');
     Route::get('/centers/create', [CenterController::class, 'create'])->name('admin.centers.create');
     Route::get('/centers/edit/{id}', [CenterController::class, 'edit'])->name('admin.centers.edit');
     Route::get('/centers/destroy/{id}', [CenterController::class, 'destroy'] )->name('admin.centers.destroy');
     Route::get('/centers/show/{id}', [CenterController::class, 'show'])->name('admin.centers.show');
     Route::post('/centers/update', [CenterController::class, 'update'])->name('admin.centers.update');
     Route::get('/centers/change-status/{id}', [CenterController::class, 'changeStatus'])->name('admin.centers.change-status');
     Route::post('/centers/store', [CenterController::class, 'store'])->name('admin.centers.store');

    
     //checkins
     Route::get('/checkins', [CheckInController::class, 'index'])->name('admin.checkins.index');
     Route::get('/checkins/create', [CheckInController::class, 'create'])->name('admin.checkins.create');
     Route::get('/checkins/edit/{id}', [CheckInController::class, 'edit'])->name('admin.checkins.edit');
     Route::get('/checkins/destroy/{id}', [CheckInController::class, 'destroy'] )->name('admin.checkins.destroy');
     Route::get('/checkins/show/{id}', [CheckInController::class, 'show'])->name('admin.checkins.show');
     Route::post('/checkins/update', [CheckInController::class, 'update'])->name('admin.checkins.update');
     Route::get('/checkins/change-status/{id}', [CheckInController::class, 'changeStatus'])->name('admin.checkins.change-status');
     Route::post('/checkins/store', [CheckInController::class, 'store'])->name('admin.checkins.store');
     Route::get('checkins/export',[CheckInController::class,'export'])->name('admin.checkins.export');
     Route::get('checkins/view',[CheckInController::class,'ViewExport'])->name('admin.checkins.viewexport');

     //guards
     Route::get('/guards', [GuardController::class, 'index'])->name('admin.guards.index');
     Route::get('/guards/create', [GuardController::class, 'create'])->name('admin.guards.create');
     Route::get('/guards/edit/{id}', [GuardController::class, 'edit'])->name('admin.guards.edit');
     Route::get('/guards/destroy/{id}', [GuardController::class, 'destroy'] )->name('admin.guards.destroy');
     Route::get('/guards/show/{id}', [GuardController::class, 'show'])->name('admin.guards.show');
     Route::post('/guards/update', [GuardController::class, 'update'])->name('admin.guards.update');
     Route::get('/guards/change-status/{id}', [GuardController::class, 'changeStatus'])->name('admin.guards.change-status');
     Route::post('/store', [GuardController::class, 'store'])->name('admin.guards.store');
     Route::get('/guards/emails/{id}', [GuardController::class, 'emails'])->name('admin.guards.emails');
     Route::post('/guards/emails/store', [GuardController::class, 'emailStore'])->name('admin.guards.emails.store');
     
       //Key Types
       Route::get('/keytypes', [KeyTypeController::class, 'index'])->name('admin.keytypes.index');
       Route::get('/keytypes/create', [KeyTypeController::class, 'create'])->name('admin.keytypes.create');
       Route::get('/keytypes/edit/{id}', [KeyTypeController::class, 'edit'])->name('admin.keytypes.edit');
       Route::get('/keytypes/destroy/{id}', [KeyTypeController::class, 'destroy'] )->name('admin.keytypes.destroy');
       Route::get('/keytypes/show/{id}', [KeyTypeController::class, 'show'])->name('admin.keytypes.show');
       Route::post('/keytypes/update', [KeyTypeController::class, 'update'])->name('admin.keytypes.update');
       Route::get('/keytypes/change-status/{id}', [KeyTypeController::class, 'changeStatus'])->name('admin.keytypes.change-status');
       Route::post('/keytypes/store', [KeyTypeController::class, 'store'])->name('admin.keytypes.store');


      //Employees
      Route::get('/employees', [EmployController::class, 'index'])->name('admin.employees.index');
      Route::get('/employees/create', [EmployController::class, 'create'])->name('admin.employees.create');
      Route::get('/employees/edit/{id}', [EmployController::class, 'edit'])->name('admin.employees.edit');
      Route::get('/employees/destroy/{id}', [EmployController::class, 'destroy'] )->name('admin.employees.destroy');
      Route::get('/employees/show/{id}', [EmployController::class, 'show'])->name('admin.employees.show');
      Route::post('/employees/update', [EmployController::class, 'update'])->name('admin.employees.update');
      Route::get('/employees/change-status/{id}', [EmployController::class, 'changeStatus'])->name('admin.employees.change-status');
      Route::post('/employees/store', [EmployController::class, 'store'])->name('admin.employees.store');
       
        //email templates
        Route::get('/email-templates', [EmailTemplateController::class, 'index'])->name('admin.email-templates.index');
        Route::get('/email-templates/create', [EmailTemplateController::class, 'create'])->name('admin.email-templates.create');
        Route::get('/email-templates/edit/{id}', [EmailTemplateController::class, 'edit'])->name('admin.email-templates.edit');
        Route::get('/email-templates/destroy/{id}', [EmailTemplateController::class, 'destroy'] )->name('admin.email-templates.destroy');
        Route::get('/email-templates/show/{id}', [EmailTemplateController::class, 'show'])->name('admin.email-templates.show');
        Route::post('/email-templates/update', [EmailTemplateController::class, 'update'])->name('admin.email-templates.update');
        Route::post('/email-templates/store', [EmailTemplateController::class, 'store'])->name('admin.email-templates.store');
        
        //students
        Route::get('/applications/show/{id}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::get('/applications', [ApplicationController::class, 'index'])->name('admin.applications.index');

        //stages
        Route::get('/stages', [StageController::class, 'index'])->name('admin.stages.index');
        Route::get('/stages/create', [StageController::class, 'create'])->name('admin.stages.create');
        Route::get('/stages/edit/{id}', [StageController::class, 'edit'])->name('admin.stages.edit');
        Route::get('/stages/destroy/{id}', [StageController::class, 'destroy'] )->name('admin.stages.destroy');
        Route::get('/stages/show/{id}', [StageController::class, 'show'])->name('admin.stages.show');
        Route::post('/stages/update', [StageController::class, 'update'])->name('admin.stages.update');
        Route::get('/stages/change-status/{id}', [StageController::class, 'changeStatus'])->name('admin.stages.change-status');
        Route::post('/stages/store', [StageController::class, 'store'])->name('admin.stages.store');
        Route::get('/stages/next-stages/{id}', [StageController::class, 'nextStages'])->name('admin.stages.next-stages');
        Route::post('/stages/next-stage-store', [StageController::class, 'nextStageStore'])->name('admin.stages.next-stage-store');

        //Substages
        Route::get('/substages/edit/{id}', [SubStageController::class, 'edit'])->name('admin.substages.edit');
        Route::get('/substages/destroy/{id}', [SubStageController::class, 'destroy'] )->name('admin.substages.destroy');
        Route::get('/substages/create/{stage_id}', [SubStageController::class, 'create'])->name('admin.substages.create');
        Route::post('/substages/update', [SubStageController::class, 'update'])->name('admin.substages.update');
        Route::get('/substages/change-status/{id}', [SubStageController::class, 'changeStatus'])->name('admin.substages.change-status');
        Route::get('/substages/change-default/{id}', [SubStageController::class, 'changedefault'])->name('admin.substages.change-default');
        Route::post('/substages/store', [SubStageController::class, 'store'])->name('admin.substages.store');
        Route::get('/substages/{stage_id}', [SubStageController::class, 'index'])->name('admin.substages.index');

        Route::group(['prefix' => 'stages'], function(){
            Route::get('/email-actions/edit/{id}', [StageEmailActionController::class, 'edit'])->name('admin.stages.email-actions.edit');
            Route::get('/email-actions/destroy/{id}', [StageEmailActionController::class, 'destroy'] )->name('admin.stages.email-actions.destroy');
            Route::get('/email-actions/create/{stage_id}', [StageEmailActionController::class, 'create'])->name('admin.stages.email-actions.create');
            Route::post('/email-actions/update', [StageEmailActionController::class, 'update'])->name('admin.stages.email-actions.update');
            Route::get('/email-actions/change-status/{id}', [StageEmailActionController::class, 'changeStatus'])->name('admin.stages.email-actions.change-status');
            Route::post('/email-actions/store', [StageEmailActionController::class, 'store'])->name('admin.stages.email-actions.store');
            Route::get('/email-actions/{stage_id}', [StageEmailActionController::class, 'index'])->name('admin.stages.email-actions.index');

            Route::get('/task-actions/edit/{id}', [StageTaskActionController::class, 'edit'])->name('admin.stages.task-actions.edit');
            Route::get('/task-actions/destroy/{id}', [StageTaskActionController::class, 'destroy'] )->name('admin.stages.task-actions.destroy');
            Route::get('/task-actions/create/{stage_id}', [StageTaskActionController::class, 'create'])->name('admin.stages.task-actions.create');
            Route::post('/task-actions/update', [StageTaskActionController::class, 'update'])->name('admin.stages.task-actions.update');
            Route::get('/task-actions/change-status/{id}', [StageTaskActionController::class, 'changeStatus'])->name('admin.stages.task-actions.change-status');
            Route::post('/task-actions/store', [StageTaskActionController::class, 'store'])->name('admin.stages.task-actions.store');
            Route::get('/task-actions/{stage_id}', [StageTaskActionController::class, 'index'])->name('admin.stages.task-actions.index');
        });

        //document templates
        Route::get('/document-templates', [DocumentTemplateController::class, 'index'])->name('admin.document-templates.index');
        Route::get('/document-templates/create', [DocumentTemplateController::class, 'create'])->name('admin.document-templates.create');
        Route::get('/document-templates/edit/{id}', [DocumentTemplateController::class, 'edit'])->name('admin.document-templates.edit');
        Route::get('/document-templates/destroy/{id}', [DocumentTemplateController::class, 'destroy'] )->name('admin.document-templates.destroy');
        Route::get('/document-templates/show/{id}', [DocumentTemplateController::class, 'show'])->name('admin.document-templates.show');
        Route::post('/document-templates/update', [DocumentTemplateController::class, 'update'])->name('admin.document-templates.update');
        Route::get('/document-templates/change-status/{id}', [DocumentTemplateController::class, 'changeStatus'])->name('admin.document-templates.change-status');
        Route::post('/document-templates/store', [DocumentTemplateController::class, 'store'])->name('admin.document-templates.store');
        Route::get('/document-templates/make-mandatory/{id}', [DocumentTemplateController::class, 'makeMandatory'])->name('admin.document-templates.make-mandatory');

        //leads
        Route::get('/leads', [LeadController::class, 'index'])->name('admin.leads.index');
        Route::get('/leads/show/{id}', [LeadController::class, 'show'])->name('admin.leads.show');
        Route::get('/leads/import', [LeadController::class, 'import'])->name('admin.leads.import');
        Route::post('/leads/import-save', [LeadController::class, 'import_save'])->name('admin.leads.import-save');

        
	});

    Route::get('/{id?}', [AuthenticateSessionOtpController::class, 'create'])->name('admin.auth.login');
});