<?php

use App\Http\Controllers\Admin\AddonsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Staff\Auth\StaffAuthenticatedSessionController; /*  Start Admin panel Controller  */
use App\Http\Controllers\Staff\ShopController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

$adminPrefix = config('custom.admin_login_prefix', 'admin');
$staffPrefix = config('custom.staff_login_prefix', 'staff');
Route::post('/admin/staff/change-status/{id}', [\App\Http\Controllers\Admin\StaffController::class , 'changeStatus'])->name('staff.change.status');
Route::get('/dashboard', [\App\Http\Controllers\Admin\StaffDashboardController::class , 'index'])->name('staff.dashboard');
Route::post('/shops/save-draft', [\App\Http\Controllers\Admin\StaffDashboardController::class , 'saveDraft'])->name('staff.shops.save-draft');
Route::get('/shops/get-draft', [\App\Http\Controllers\Admin\StaffDashboardController::class , 'getDraft'])->name('staff.shops.get-draft');
Route::post('/shops/clear-draft', [\App\Http\Controllers\Admin\StaffDashboardController::class , 'clearDraft'])->name('staff.shops.clear-draft');
Route::post('/shops/direct-save', [\App\Http\Controllers\Admin\StaffDashboardController::class , 'directSave'])->name('staff.shops.direct-save'); // New route


if ($adminPrefix !== 'admin') {
    Route::prefix($adminPrefix)->name('admin.')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class , 'create'])->name('login');
        Route::post('store-login', [AuthenticatedSessionController::class , 'store'])->name('store-login');
        Route::get('forgot-password', [PasswordResetLinkController::class , 'create'])->name('password.request');
        Route::post('/forget-password', [PasswordResetLinkController::class , 'custom_forget_password'])->name('forget-password');
        Route::get('reset-password/{token}', [NewPasswordController::class , 'custom_reset_password_page'])->name('password.reset');
        Route::post('/reset-password-store/{token}', [NewPasswordController::class , 'custom_reset_password_store'])->name('password.reset-store');
    });
}
else {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class , 'create'])->name('login');
        Route::post('store-login', [AuthenticatedSessionController::class , 'store'])->name('store-login');
        Route::get('forgot-password', [PasswordResetLinkController::class , 'create'])->name('password.request');
        Route::post('/forget-password', [PasswordResetLinkController::class , 'custom_forget_password'])->name('forget-password');
        Route::get('reset-password/{token}', [NewPasswordController::class , 'custom_reset_password_page'])->name('password.reset');
        Route::post('/reset-password-store/{token}', [NewPasswordController::class , 'custom_reset_password_store'])->name('password.reset-store');
    });
}

if ($adminPrefix !== 'staff') {
    Route::prefix($staffPrefix)->name('staff.')->group(function () {
        Route::get('login', [StaffAuthenticatedSessionController::class , 'create'])->name('login');
        Route::post('store-login', [StaffAuthenticatedSessionController::class , 'store'])->name('store-login');
        Route::get('forgot-password', [PasswordResetLinkController::class , 'create'])->name('password.request');
        Route::post('/forget-password', [PasswordResetLinkController::class , 'custom_forget_password'])->name('forget-password');
        Route::get('reset-password/{token}', [NewPasswordController::class , 'custom_reset_password_page'])->name('password.reset');
        Route::post('/reset-password-store/{token}', [NewPasswordController::class , 'custom_reset_password_store'])->name('password.reset-store');
        // Simple Shop Routes for Staff
        Route::get('/shops', [App\Http\Controllers\Staff\ShopController::class , 'index'])->name('shop.index');
        Route::get('/shops/create', [App\Http\Controllers\Staff\ShopController::class , 'create'])->name('shop.create');
        Route::post('/shops', [App\Http\Controllers\Staff\ShopController::class , 'store'])->name('shop.store');
        Route::get('/shops/{id}', [App\Http\Controllers\Staff\ShopController::class , 'show'])->name('shop.show');
        Route::get('/shops/{id}/edit', [App\Http\Controllers\Staff\ShopController::class , 'edit'])->name('shop.edit');
        Route::put('/shops/{id}', [App\Http\Controllers\Staff\ShopController::class , 'update'])->name('shop.update');
        Route::delete('/shops/{id}', [App\Http\Controllers\Staff\ShopController::class , 'destroy'])->name('shop.destroy');

        // Simple Photo Routes for Staff
        Route::delete('/shop-photo/{photoId}', [App\Http\Controllers\Staff\StaffShopController::class , 'deletePhoto'])->name('shop.delete-photo');
        Route::put('/shop-photo/{photoId}/primary', [App\Http\Controllers\Staff\StaffShopController::class , 'setPrimaryPhoto'])->name('shop.set-primary-photo');


        Route::post('logout', [StaffAuthenticatedSessionController::class , 'destroy'])
            ->name('logout');
    });
}
else {
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('login', [StaffAuthenticatedSessionController::class , 'create'])->name('login');
        Route::post('store-login', [StaffAuthenticatedSessionController::class , 'store'])->name('store-login');
        Route::get('forgot-password', [PasswordResetLinkController::class , 'create'])->name('password.request');
        Route::post('/forget-password', [PasswordResetLinkController::class , 'custom_forget_password'])->name('forget-password');
        Route::get('reset-password/{token}', [NewPasswordController::class , 'custom_reset_password_page'])->name('password.reset');
        Route::post('/reset-password-store/{token}', [NewPasswordController::class , 'custom_reset_password_store'])->name('password.reset-store');

        Route::post('logout', [StaffAuthenticatedSessionController::class , 'destroy'])
            ->name('logout');
    });
}

Route::prefix('staff')->name('staff.')->middleware(['auth:staff'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Staff\StaffboardController::class , 'dashboard'])->name('dashboard');

    Route::controller(\App\Http\Controllers\Staff\ProfileController::class)->group(function () {
            Route::get('edit-profile', 'edit_profile')->name('edit-profile');
            Route::put('profile-update', 'profile_update')->name('profile-update');
            Route::put('update-password', 'update_password')->name('update-password');
        }
        );
    });


Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
    /* Start admin auth route */
    Route::post('logout', [AuthenticatedSessionController::class , 'destroy'])->name('logout');


    /* End admin auth route */

    Route::middleware(['auth:admin'])->group(function () {
            Route::get('/', [DashboardController::class , 'dashboard']);
            Route::get('dashboard', [DashboardController::class , 'dashboard'])->name('dashboard');

            Route::resource('country', CountryController::class);
            Route::resource('city', CityController::class);
            Route::resource('state', StateController::class);
            Route::get('/all-states-by-country/{id}', [StateController::class , 'getAllStateByCountry'])->name('get.all.states.by.country');

            Route::get('/all-cities-by-state/{id}', [CityController::class , 'getAllCitiesByState'])->name('get.all.cities.by.state');

            Route::controller(AdminProfileController::class)->group(function () {
                    Route::get('edit-profile', 'edit_profile')->name('edit-profile');
                    Route::put('profile-update', 'profile_update')->name('profile-update');
                    Route::put('update-password', 'update_password')->name('update-password');
                }
                );

                Route::get('role/assign', [RolesController::class , 'assignRoleView'])->name('role.assign');
                Route::post('role/assign/{id}', [RolesController::class , 'getAdminRoles'])->name('role.assign.admin');
                Route::put('role/assign', [RolesController::class , 'assignRoleUpdate'])->name('role.assign.update');
                Route::resource('/role', RolesController::class);

                Route::resource('admin', AdminController::class)->except('show');
                Route::put('admin-status/{id}', [AdminController::class , 'changeStatus'])->name('admin.status');

                Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class);

                Route::get('settings', [SettingController::class , 'settings'])->name('settings');
                Route::get('sync-modules', [AddonsController::class , 'syncModules'])->name('addons.sync');
            }
            );
        });
