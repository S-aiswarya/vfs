<?php

use App\Http\Controllers\Apis\ApplicationController;
use App\Http\Controllers\Apis\CommunicationLogController;
use App\Http\Controllers\Apis\CourseController;
use App\Http\Controllers\Apis\DashboardController;
use App\Http\Controllers\Apis\DocumentController;
use App\Http\Controllers\Apis\EmailController;
use App\Http\Controllers\Apis\EmailTemplateController;
use App\Http\Controllers\Apis\EventController;
use App\Http\Controllers\Apis\EventRegistrationController;
use App\Http\Controllers\Apis\FollowUpController;
use App\Http\Controllers\Apis\LeadController;
use App\Http\Controllers\Apis\LeadNoteController;
use App\Http\Controllers\Apis\ListController;
use App\Http\Controllers\Apis\PaymentController;
use App\Http\Controllers\Apis\PhoneCallController;
use App\Http\Controllers\Apis\ReferralLinkController;
use App\Http\Controllers\Apis\TravellerAuthController;
use App\Http\Controllers\Apis\StudentController;
use App\Http\Controllers\Apis\TargetGoalsController;
use App\Http\Controllers\Apis\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\UserAuthController;
use App\Http\Controllers\Apis\TaskChecklistController;
use App\Http\Controllers\Apis\TaskNoteController;
use App\Http\Controllers\Apis\VisitorLogController;
use App\Http\Controllers\Apis\WhatsappController;
use App\Http\Controllers\Apis\WhatsappTemplateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [UserAuthController::class, 'login'])->name('app.login');
Route::post('forgot-password', [UserAuthController::class, 'forgot_password'])->name('app.forgot-password');
Route::post('forgot-password-save', [UserAuthController::class, 'forgot_password_save'])->name('app.forgot-password-save');
Route::get('referral-links/form/{token}', [ReferralLinkController::class, 'form'])->name('app.referral-links.form');
Route::post('leads/public/store', [LeadController::class, 'publicStore'])->name('app.leads.public.store');
Route::get('events/form/{token}', [EventController::class, 'form'])->name('app.events.view');
Route::post('events/registrations/store', [EventRegistrationController::class, 'store'])->name('app.events.registrations.store');

Route::group(['middleware' => ['auth:sanctum', 'type.user']], function(){
    Route::get('get-user', [UserAuthController::class, 'getUser'])->name('app.get-user');
    Route::post('user/change-password', [UserAuthController::class, 'changePassword'])->name('app.change-password');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('app.dashboard');
    Route::post('checkin', [VisitorLogController::class, 'Checkin'])->name('app.checkin');
    Route::post('checkout', [VisitorLogController::class, 'Checkout'])->name('app.checkout');
    Route::get('checkin', [VisitorLogController::class, 'index'])->name('app.checkin');


    Route::group(['middleware' => ['type.sales']], function(){
        Route::get('leads', [LeadController::class, 'index'])->name('app.leads.list');
        Route::post('leads/store', [LeadController::class, 'store'])->name('app.leads.store');
        Route::post('leads/update', [LeadController::class, 'update'])->name('app.leads.update');
        Route::get('leads/view/{id}', [LeadController::class, 'view'])->name('app.leads.list.view');
        Route::post('leads/delete', [LeadController::class, 'delete'])->name('app.leads.delete');
        Route::post('leads/restore', [LeadController::class, 'restore'])->name('app.leads.restore');
        Route::get('leads/timeline/{id}', [LeadController::class, 'timeline'])->name('app.leads.list.timeline');
        Route::post('leads/change-stage', [LeadController::class, 'changeStage'])->name('app.leads.change-stage');
        Route::post('leads/close', [LeadController::class, 'close'])->name('app.leads.close');
        Route::post('leads/reopen', [LeadController::class, 'reopen'])->name('app.leads.reopen');
        Route::post('leads/bulk-assign', [LeadController::class, 'bulkAssign'])->name('app.leads.bulk-assign');
        Route::post('leads/round-robin-assign', [LeadController::class, 'roundRobinAssign'])->name('app.leads.round-robin-assign');
        Route::get('leads/notes-and-followups/{id}', [LeadController::class, 'notesFollowUps'])->name('app.leads.notes-and-followups');

    });

    Route::group(['prefix' => 'tasks'], function(){
        Route::get('/', [TaskController::class, 'index'])->name('app.tasks.index');
        Route::post('store', [TaskController::class, 'store'])->name('app.tasks.store');
        Route::post('update', [TaskController::class, 'update'])->name('app.tasks.update');
        Route::get('view/{id}', [TaskController::class, 'view'])->name('app.tasks.view');
        Route::post('delete', [TaskController::class, 'delete'])->name('app.tasks.delete');
        Route::post('change-status', [TaskController::class, 'changeStatus'])->name('app.tasks.change-status');
        Route::post('archive', [TaskController::class, 'archive'])->name('app.tasks.archive');
        Route::post('reopen', [TaskController::class, 'reopen'])->name('app.tasks.reopen');
        Route::get('timeline/{id}', [TaskController::class, 'timeline'])->name('app.tasks.timeline');

        Route::get('notes/{task_id}', [TaskNoteController::class, 'index'])->name('app.tasks.notes.index');
        Route::post('notes/store', [TaskNoteController::class, 'store'])->name('app.tasks.notes.store');
        Route::post('notes/update', [TaskNoteController::class, 'update'])->name('app.tasks.notes.update');
        Route::get('notes/view/{id}', [TaskNoteController::class, 'view'])->name('app.tasks.notes.view');
        Route::post('notes/delete', [TaskNoteController::class, 'delete'])->name('app.tasks.notes.delete');

        Route::get('checklists/{task_id}', [TaskChecklistController::class, 'index'])->name('app.tasks.checklists.index');
        Route::post('checklists/store', [TaskChecklistController::class, 'store'])->name('app.tasks.checklists.store');
        Route::post('checklists/update', [TaskChecklistController::class, 'update'])->name('app.tasks.checklists.update');
        Route::get('checklists/view/{id}', [TaskChecklistController::class, 'view'])->name('app.tasks.checklists.view');
        Route::post('checklists/delete', [TaskChecklistController::class, 'delete'])->name('app.tasks.checklists.delete');
        Route::post('checklists/completed', [TaskChecklistController::class, 'completed'])->name('app.tasks.checklists.completed');
    });

    Route::get('documents', [DocumentController::class, 'index'])->name('app.documents.index');
    Route::get('documents/view/{id}', [DocumentController::class, 'view'])->name('app.documents.view');
    Route::post('documents/store', [DocumentController::class, 'store'])->name('app.documents.store');
    Route::post('documents/update', [DocumentController::class, 'update'])->name('app.documents.update');
    Route::post('documents/request', [DocumentController::class, 'request'])->name('app.documents.request');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('app.documents.upload');
    Route::post('documents/accept', [DocumentController::class, 'accept'])->name('app.documents.accept');
    Route::post('documents/reject', [DocumentController::class, 'reject'])->name('app.documents.reject');

    Route::get('students', [StudentController::class, 'index'])->name('app.students.index');
    Route::post('students/create', [StudentController::class, 'create'])->name('app.students.store');
    Route::post('students/update', [StudentController::class, 'update'])->name('app.students.update');
    Route::get('students/view/{id}', [StudentController::class, 'view'])->name('app.students.view');
    Route::post('students/close', [StudentController::class, 'close'])->name('app.students.close');

    Route::get('follow-ups/{lead_id}', [FollowUpController::class, 'index'])->name('app.follow-ups.index');
    Route::post('follow-ups/store', [FollowUpController::class, 'store'])->name('app.follow-ups.store');
    Route::post('follow-ups/update', [FollowUpController::class, 'update'])->name('app.follow-ups.update');
    Route::get('follow-ups/view/{id}', [FollowUpController::class, 'view'])->name('app.follow-ups.view');
    Route::post('follow-ups/completed', [FollowUpController::class, 'completed'])->name('app.follow-ups.delete');
    Route::post('follow-ups/delete', [FollowUpController::class, 'delete'])->name('app.follow-ups.delete');

    Route::get('applications', [ApplicationController::class, 'index'])->name('app.applications.index');
    Route::post('applications/store', [ApplicationController::class, 'store'])->name('app.applications.store');
    Route::post('applications/update', [ApplicationController::class, 'update'])->name('app.applications.update');
    Route::get('applications/view/{id}', [ApplicationController::class, 'view'])->name('app.applications.view');
    Route::get('applications/timeline/{id}', [ApplicationController::class, 'timeline'])->name('app.applications.list.timeline');

    Route::post('applications/change-stage', [ApplicationController::class, 'changeStage'])->name('app.applications.change-stage');
    Route::post('applications/upload-university-document', [ApplicationController::class, 'uploadUniversityDocument'])->name('app.applications.upload-university-document');
    Route::post('applications/delete-university-document', [ApplicationController::class, 'deleteUniversityDocument'])->name('app.applications.delete-university-document');

    Route::post('applications/save-university-id', [ApplicationController::class, 'saveUniversityId'])->name('app.applications.save-university-id');

    Route::get('email-templates/template/{template_id}/{type}/{id}', [EmailTemplateController::class, 'template'])->name('app.email-templates.template.view');
    

    Route::group(['middleware' => ['type.manager']], function(){
        Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('app.email-templates.index');
        Route::post('email-templates/store', [EmailTemplateController::class, 'store'])->name('app.email-templates.store');
        Route::post('email-templates/update', [EmailTemplateController::class, 'update'])->name('app.email-templates.update');
        Route::get('email-templates/view/{id}', [EmailTemplateController::class, 'view'])->name('app.email-templates.view');
        Route::post('email-templates/file-uploads', [EmailTemplateController::class, 'fileUploads'])->name('app.email-templates.file-uploads');

        Route::get('courses', [CourseController::class, 'index'])->name('app.courses.index');
        Route::post('courses/store', [CourseController::class, 'store'])->name('app.courses.store');
        Route::post('courses/update', [CourseController::class, 'update'])->name('app.courses.update');
        Route::get('courses/view/{id}', [CourseController::class, 'view'])->name('app.courses.view');

        Route::get('events', [EventController::class, 'index'])->name('app.events.index');
        Route::post('events/store', [EventController::class, 'store'])->name('app.events.store');
        Route::post('events/update', [EventController::class, 'update'])->name('app.events.update');
        Route::get('events/view/{id}', [EventController::class, 'view'])->name('app.events.view');

        Route::get('whatsapp-templates', [WhatsappTemplateController::class, 'index'])->name('app.whatsapp-templates.index');
        Route::post('whatsapp-templates/store', [WhatsappTemplateController::class, 'store'])->name('app.whatsapp-templates.store');
        Route::post('whatsapp-templates/update', [WhatsappTemplateController::class, 'update'])->name('app.whatsapp-templates.update');
        Route::get('whatsapp-templates/view/{id}', [WhatsappTemplateController::class, 'view'])->name('app.whatsapp-templates.view');
        Route::get('whatsapp-templates/template/{template_id}/{type}/{id}', [WhatsappTemplateController::class, 'template'])->name('app.whatsapp-templates.template.view');

        Route::post('leads/import', [LeadController::class, 'import'])->name('app.leads.import');
    });

    Route::get('leads/notes/{lead_id}', [LeadNoteController::class, 'index'])->name('app.leads.notes.index');
    Route::post('leads/notes/store', [LeadNoteController::class, 'store'])->name('app.leads.notes.store');
    Route::post('leads/notes/update', [LeadNoteController::class, 'update'])->name('app.leads.notes.update');
    Route::get('leads/notes/view/{id}', [LeadNoteController::class, 'view'])->name('app.leads.notes.view');
    Route::post('leads/notes/delete', [LeadNoteController::class, 'delete'])->name('app.leads.notes.delete');

    

    Route::get('emails', [EmailController::class, 'index'])->name('app.emails.index');
    Route::post('emails/store', [EmailController::class, 'store'])->name('app.emails.store');
    Route::get('emails/view/{id}', [EmailController::class, 'view'])->name('app.emails.view');

    Route::get('whatsapp', [WhatsappController::class, 'index'])->name('app.whatsapp.index');
    Route::post('whatsapp/store', [WhatsappController::class, 'store'])->name('app.whatsapp.store');
    Route::get('whatsapp/view/{id}', [WhatsappController::class, 'view'])->name('app.whatsapp.view');

    Route::get('payments', [PaymentController::class, 'index'])->name('app.payments.index');
    Route::post('payments/store', [PaymentController::class, 'store'])->name('app.payments.store');
    Route::post('payments/update', [PaymentController::class, 'update'])->name('app.payments.update');
    Route::get('payments/view/{id}', [PaymentController::class, 'view'])->name('app.payments.view');

    Route::get('referral-links', [ReferralLinkController::class, 'index'])->name('app.referral-links.index');
    Route::post('referral-links/store', [ReferralLinkController::class, 'store'])->name('app.referral-links.store');
    Route::post('referral-links/update', [ReferralLinkController::class, 'update'])->name('app.referral-links.update');
    Route::get('referral-links/view/{id}', [ReferralLinkController::class, 'view'])->name('app.referral-links.view');


    Route::get('events/registrations', [EventRegistrationController::class, 'index'])->name('app.events.registrations.index');
    Route::get('events/registrations/view/{id}', [EventRegistrationController::class, 'view'])->name('app.events.registrations.view');

    Route::get('communication-log', [CommunicationLogController::class, 'index'])->name('app.communication-log.index');
    Route::get('communication-log/view/{id}', [CommunicationLogController::class, 'view'])->name('app.communication-log.view');
    Route::get('communication-log/summary', [CommunicationLogController::class, 'summary'])->name('app.communication-log.summary');

    Route::get('targets-and-goals', [TargetGoalsController::class, 'index'])->name('app.targets-and-goals');

    Route::post('phone-calls/store', [PhoneCallController::class, 'store'])->name('app.phone-calls.store');
    Route::post('phone-calls/update', [PhoneCallController::class, 'update'])->name('app.phone-calls.update');
    Route::get('phone-calls/view/{id}', [PhoneCallController::class, 'view'])->name('app.phone-calls.view');
    Route::post('phone-calls/delete', [PhoneCallController::class, 'delete'])->name('app.phone-calls.delete');
    Route::get('phone-calls/summary', [PhoneCallController::class, 'summary'])->name('app.phone-calls.summary');
    Route::get('phone-calls/{lead_id}', [PhoneCallController::class, 'index'])->name('app.phone-calls.index');


    
    Route::get('listing/register-types/groups', [ListController::class, 'register_type_groups'])->name('app.listings.register_type_groups');
    Route::get('listing/register-types/groups', [ListController::class, 'register_type_groups'])->name('app.listings.register_type_groups');
    Route::get('listing/register-types', [ListController::class, 'register_types'])->name('app.listings.register_types');
    Route::get('listing/countries', [ListController::class, 'countries'])->name('app.listings.countries');
    Route::get('listing/applications', [ListController::class, 'applications'])->name('app.listings.applications');
    Route::get('listing/subject-areas', [ListController::class, 'subject_areas'])->name('app.listings.subject-areas');
    Route::get('listing/stages', [ListController::class, 'stages'])->name('app.listings.stages');
    Route::get('listing/substages', [ListController::class, 'substages'])->name('app.listings.substages');
    Route::get('listing/agencies', [ListController::class, 'agencies'])->name('app.listings.agencies');
    Route::get('listing/users', [ListController::class, 'users'])->name('app.listings.users');
    Route::get('listing/document-templates', [ListController::class, 'documentTemplates'])->name('app.listings.document-templates');
    Route::get('listing/global-countries', [ListController::class, 'globalCountries'])->name('app.listings.global-countries');
    Route::get('listing/referrals', [ListController::class, 'referrals'])->name('app.listings.referrals');
    Route::get('listing/course-levels', [ListController::class, 'courseLevels'])->name('app.listings.course-levels');
    Route::get('listing/intakes', [ListController::class, 'intakes'])->name('app.listings.intakes');
    Route::get('listing/titles', [ListController::class, 'titles'])->name('app.listings.titles');
    Route::get('listing/lead-sources', [ListController::class, 'leadSources'])->name('app.listings.lead-sources');
    Route::get('listing/roles', [ListController::class, 'roles'])->name('app.listings.roles');
    Route::get('listing/permissions', [ListController::class, 'permissions'])->name('app.listings.permissions');
    Route::get('listing/email-templates', [ListController::class, 'emailTemplates'])->name('app.listings.email-templates');
    Route::get('listing/whatsapp-templates', [ListController::class, 'whatsappTemplates'])->name('app.listings.whatsapp-templates');
    Route::get('listing/offices', [ListController::class, 'offices'])->name('app.listings.offices');
    Route::get('listing/events', [ListController::class, 'events'])->name('app.listings.events');
    Route::get('listing/lead-archive-reasons', [ListController::class, 'lead_archive_reasons'])->name('app.listings.lead-archive-reasons');
    Route::get('listing/phone-call-statuses', [ListController::class, 'phone_call_statuses'])->name('app.listings.phone-call-statuses');
    Route::get('listing/university-countries', [ListController::class, 'universityCountries'])->name('app.listings.university-countries');
    Route::get('listing/students', [ListController::class, 'students'])->name('app.listings.students');
    Route::get('listing/next-stages/{current_stage}', [ListController::class, 'nextStages'])->name('app.listings.next-stages');
    
});


Route::get('whatsapp/webhook', [WhatsappController::class, 'webhookVerify'])->name('app.whatsapp.webhook.verify');
Route::post('whatsapp/webhook', [WhatsappController::class, 'webhookStore'])->name('app.whatsapp.webhook.store');

Route::get('max-file-upload-size', function(){
    return response()->json(['size' => (int)ini_get("upload_max_filesize")]);
})->name('app.max-file-upload-size');

Route::group(['prefix' => 'traveller'], function(){
    Route::post('login', [TravellerAuthController::class, 'login'])->name('app.traveller.login');

    Route::group(['middleware' => ['auth:sanctum', 'type.traveller']], function(){

        Route::post('change-password', [UserAuthController::class, 'changePassword'])->name('app.traveller.change-password');

        Route::get('dashboard', [DashboardController::class, 'documentStatus'])->name('app.traveller.dashboard');

        Route::get('documents', [DocumentController::class, 'index'])->name('app.traveller.documents.index');
        Route::get('documents/view/{id}', [DocumentController::class, 'view'])->name('app.traveller.documents.view');
        Route::post('documents/upload', [DocumentController::class, 'update'])->name('app.traveller.documents.update');

        Route::get('applications', [ApplicationController::class, 'index'])->name('app.traveller.applications.index');
        Route::get('applications/view/{id}', [ApplicationController::class, 'view'])->name('app.traveller.applications.view');

        Route::get('phone-calls/summary', [PhoneCallController::class, 'summary'])->name('app.traveller.phone-calls.summary');
        Route::get('phone-calls/{lead_id}', [PhoneCallController::class, 'index'])->name('app.traveller.phone-calls.index');

        Route::get('communication-log', [CommunicationLogController::class, 'index'])->name('app.traveller.communication-log.index');
        Route::get('communication-log/view/{id}', [CommunicationLogController::class, 'view'])->name('app.traveller.communication-log.view');
        Route::get('communication-log/summary', [CommunicationLogController::class, 'summary'])->name('app.traveller.communication-log.summary');

        Route::get('leads/view/{id}', [LeadController::class, 'view'])->name('app.traveller.leads.list.view');
        
    });
});

