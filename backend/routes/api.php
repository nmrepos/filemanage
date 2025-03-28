<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DocumentPermissionController;
use App\Http\Controllers\RoleUsersController;
use App\Http\Controllers\ActionsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LoginAuditController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserClaimController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login', 'login');
    Route::post('auth/logout', 'logout');
});



Route::middleware(['auth'])->group(function () {
    Route::post('auth/refresh', [AuthController::class, 'refresh']);


    Route::middleware('hasToken:LOGIN_AUDIT_VIEW_LOGIN_AUDIT_LOGS')->group(function () {
        Route::get('/loginAudit', [LoginAuditController::class, 'getLoginAudit']);
    });
    //languages
    Route::post('/languages', [LanguageController::class, 'saveLanguage']);
    Route::delete('/languages/{id}', [LanguageController::class, 'deleteLanguage']);
    Route::get('/languages', [LanguageController::class, 'getLanguages']);
    Route::get('/defaultlanguage', [LanguageController::class, 'defaultlanguage']);
    Route::get('/languageById/{id}', [LanguageController::class, 'getFileContentById']);

    Route::middleware('hasToken:DASHBOARD_VIEW_DASHBOARD')->group(function () {
        Route::get('/dashboard/reminders/{month}/{year}', [DashboardController::class, 'getReminders']);
        Route::get('/Dashboard/GetDocumentByCategory', [DocumentController::class, 'getDocumentsByCategoryQuery']);
    });

    Route::get('/userNotification/notification', [UserNotificationController::class, 'index']);
    Route::get('/userNotification/notifications', [UserNotificationController::class, 'getNotifications']);
    Route::post('/userNotification/MarkAsRead', [UserNotificationController::class, 'markAsRead']);
    Route::post('/UserNotification/MarkAllAsRead', [UserNotificationController::class, 'markAllAsRead']);

    
    Route::get('/role-dropdown', [RoleController::class, 'dropdown']);

    Route::group(['middleware' => ['hasToken:ROLE_VIEW_ROLES']], function () {
        Route::get('/role', [RoleController::class, 'index']);
    });

    Route::middleware('hasToken:ROLE_CREATE_ROLE')->group(function () {
        Route::post('/role', [RoleController::class, 'create']);
    });

    Route::middleware('hasToken:ROLE_EDIT_ROLE')->group(function () {
        Route::put('/role/{id}', [RoleController::class, 'update']);
    });

    Route::middleware('hasToken:ROLE_DELETE_ROLE')->group(function () {
        Route::delete('/role/{id}', [RoleController::class, 'destroy']);
    });

    Route::middleware('hasToken:ROLE_EDIT_ROLE')->group(function () {
        Route::get('/role/{id}', [RoleController::class, 'edit']);
    });

    Route::group(['middleware' => ['hasToken:ALL_DOCUMENTS_SHARE_DOCUMENT,ASSIGNED_DOCUMENTS_SHARE_DOCUMENT']], function () {
        Route::get('/DocumentRolePermission/{id}', [DocumentPermissionController::class, 'edit']);
    });

    Route::group(['middleware' => ['hasToken:ALL_DOCUMENTS_SHARE_DOCUMENT,ASSIGNED_DOCUMENTS_SHARE_DOCUMENT']], function () {
        Route::post('/documentRolePermission', [DocumentPermissionController::class, 'addDocumentRolePermission']);
    });

    Route::group(['middleware' => ['hasToken:ALL_DOCUMENTS_SHARE_DOCUMENT,ASSIGNED_DOCUMENTS_SHARE_DOCUMENT']], function () {
        Route::post('/documentRolePermission/multiple', [DocumentPermissionController::class, 'multipleDocumentsToUsersAndRoles']);
    });

    Route::group(['middleware' => ['hasToken:ALL_DOCUMENTS_SHARE_DOCUMENT,ASSIGNED_DOCUMENTS_SHARE_DOCUMENT']], function () {
        Route::delete('/documentRolePermission/{id}', [DocumentPermissionController::class, 'deleteDocumentRolePermission']);
    });

    Route::middleware('hasToken:USER_ASSIGN_USER_ROLE')->group(function () {
        Route::get('/roleusers/{roleId}', [RoleUsersController::class, 'getRoleUsers']);
    });

    Route::middleware('hasToken:USER_ASSIGN_USER_ROLE')->group(function () {
        Route::put('/roleusers/{roleId}', [RoleUsersController::class, 'updateRoleUsers']);
    });

    Route::get('/actions', [ActionsController::class, 'index']);
    Route::post('/actions', [ActionsController::class, 'create']);
    Route::put('/actions/{id}', [ActionsController::class, 'update']);
    Route::delete('/actions/{id}', [ActionsController::class, 'destroy']);

    Route::get('/pages', [PagesController::class, 'index']);
    Route::post('/pages', [PagesController::class, 'create']);
    Route::put('/pages/{id}', [PagesController::class, 'update']);
    Route::delete('/pages/{id}', [PagesController::class, 'destroy']);

    //user
    
    Route::group(['middleware' => ['hasToken:USER_VIEW_USERS']], function () {
        Route::get('/user', [UserController::class, 'index']);
    });

    Route::get('/user-dropdown', [UserController::class, 'dropdown']);

    Route::middleware('hasToken:USER_CREATE_USER')->group(function () {
        Route::post('/user', [UserController::class, 'create']);
    });

    Route::middleware('hasToken:USER_EDIT_USER')->group(function () {
        Route::put('/user/{id}', [UserController::class, 'update']);
    });

    Route::middleware('hasToken:USER_DELETE_USER')->group(function () {
        Route::delete('/user/{id}', [UserController::class, 'destroy']);
    });

    Route::middleware('hasToken:USER_EDIT_USER')->group(function () {
        Route::get('/user/{id}', [UserController::class, 'edit']);
    });

    Route::middleware('hasToken:USER_RESET_PASSWORD')->group(function () {
        Route::post('/user/resetpassword', [UserController::class, 'submitResetPassword']);
    });

    Route::post('/user/changepassword', [UserController::class, 'changePassword']);

    Route::put('/users/profile', [UserController::class, 'updateUserProfile']);

    Route::middleware('hasToken:USER_ASSIGN_PERMISSION')->group(function () {
        Route::put('/userClaim/{id}', [UserClaimController::class, 'update']);
    });

    //reminder
    
    Route::middleware('hasToken:REMINDER_VIEW_REMINDERS')->group(function () {
        Route::get('/reminder/all', [ReminderController::class, 'getReminders']);
    });

    Route::middleware('hasToken:REMINDER_CREATE_REMINDER')->group(function () {
        Route::post('/reminder', [ReminderController::class, 'addReminder']);
    });

    Route::middleware('hasToken:REMINDER_EDIT_REMINDER')->group(function () {
        Route::get('/reminder/{id}', [ReminderController::class, 'edit']);
    });

    Route::middleware('hasToken:REMINDER_EDIT_REMINDER')->group(function () {
        Route::put('/reminder/{id}', [ReminderController::class, 'updateReminder']);
    });

    Route::middleware('hasToken:REMINDER_DELETE_REMINDER')->group(function () {
        Route::delete('/reminder/{id}', [ReminderController::class, 'deleteReminder']);
    });

    Route::get('/reminder/all/currentuser', [ReminderController::class, 'getReminderForLoginUser']);

    Route::delete('/reminder/currentuser/{id}', [ReminderController::class, 'deleteReminderCurrentUser']);

    Route::post('/reminder/document', [ReminderController::class, 'addReminder']);
    Route::get('/reminder/{id}/myreminder', [ReminderController::class, 'edit']);


    //category
    Route::get('/category/dropdown', [CategoryController::class, 'GetAllCategoriesForDropDown']);
    Route::middleware('hasToken:DOCUMENT_CATEGORY_MANAGE_DOCUMENT_CATEGORY')->group(function () {
        Route::get('category', [CategoryController::class, 'index']);
        Route::post('/category', [CategoryController::class, 'create']);
        Route::put('/category/{id}', [CategoryController::class, 'update']);
        Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
        Route::get('/category/{id}/subcategories', [CategoryController::class, 'subcategories']);
    });
});


Route::get('/i18n/{fileName}', [LanguageController::class, 'downloadFile']);

