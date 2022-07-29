<?php

use App\Http\Controllers\DzzController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTasksController;
use App\Http\Controllers\UserFilesController;
use App\Http\Controllers\UserGroupsController;
use App\Http\Controllers\UserNotificationsController;
use App\Models\Satelite;
use App\Models\SateliteType;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
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

Route::post('files/polygon', [FileController::class, 'polygon']);

Route::resource('plans', PlanController::class);
Route::resource('images', ImageController::class);
Route::resource('dzzs', DzzController::class);

Route::get('satelites', function () {
    $satelitesTypes = SateliteType::all();
    $res = [];
    foreach ($satelitesTypes as $st) {
        $satelites = [];
        foreach ($st->satelites as $s) {
            array_push($satelites, [
                'id' => $s->id,
                'name' => $s->name
            ]);
        };
        array_push($res, [
            'id' => $st->id,
            'name' => $st->name,
            'satelites' => $satelites
        ]);
    }
    return response()->json([
        'satelites' => $res
    ]);
});

Route::get('user/auth', [UserController::class, 'auth']);
Route::get('user/check-auth', [UserController::class, 'checkAuth']);



// Route::middleware('admin')->resource('tasks', TaskController::class);
Route::resource('users', UserController::class);
Route::resource('groups', GroupController::class);

Route::post('groups/{group}/users', [GroupController::class, 'addUsers']);
Route::get('group-types', [GroupController::class, 'getTypes']);
Route::delete('groups/{group}/users/{user}', [GroupController::class, 'excludeUser']);

Route::post('users/{user}/block', [UserController::class, 'block']);
Route::post('users/{user}/unblock', [UserController::class, 'unblock']);
Route::get('users/{user}/logs', [UserController::class, 'getLogs']);
        
Route::resource('tasks', TaskController::class);
Route::resource('files', FileController::class);

Route::get('user/notifications', [UserNotificationsController::class, 'index']);
Route::put('user/notifications/{notification}', [UserNotificationsController::class, 'update']);
Route::get('user/notifications/unread-count', [UserNotificationsController::class, 'unreadCount']);
Route::delete('user/notifications/{notification}', [UserNotificationsController::class, 'destroy']);

Route::group(['middleware' => ['auth:sanctum', 'not-blocked']], function () {
    Route::get('download', [FileController::class, 'download']);

    Route::get('user/files', [UserFilesController::class, 'index']);
    Route::delete('user/files', [UserFilesController::class, 'destroyBanch']);
    Route::delete('user/files/{file}', [UserFilesController::class, 'destroy']);

    Route::get('user/tasks', [UserTasksController::class, 'index']);
    Route::get('user/tasks/{task}', [UserTasksController::class, 'show']);
    Route::put('user/tasks/{task}', [UserTasksController::class, 'update']);
    Route::post('user/tasks', [UserTasksController::class, 'store']);
    Route::delete('user/tasks', [UserTasksController::class, 'destroyBanch']);
    Route::delete('user/tasks/{task}', [UserTasksController::class, 'destroy']);

    Route::get('user/join-group', [UserGroupsController::class, 'join'])->name('join-group');
    Route::get('user/groups', [UserGroupsController::class, 'index']);
    Route::post('user/groups', [UserGroupsController::class, 'store']);
    Route::delete('user/groups', [UserGroupsController::class, 'destroyBanch']);
    Route::get('user/groups/{group}', [UserGroupsController::class, 'show']);
    Route::put('user/groups/{group}', [UserGroupsController::class, 'update']);
    Route::delete('user/groups/{group}', [UserGroupsController::class, 'destroy']);
    Route::delete('user/groups/{group}/quit', [UserGroupsController::class, 'quit']);
    Route::get('user/groups/{group}/invite', [UserGroupsController::class, 'generateInvite']);
    Route::get('user/groups/{group}/users', [UserGroupsController::class, 'getUsersByGroup']);
    Route::delete('user/groups/{group}/users/{user}', [UserGroupsController::class, 'excludeUser']);
    Route::put('user/groups/{group}/users/{user}', [UserGroupsController::class, 'verifyUser']);
   
});



















Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $enableViews = config('fortify.views', true);

    // Authentication...
    if ($enableViews) {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware(['guest:' . config('fortify.guard')])
            ->name('login');
    }

    $limiter = config('fortify.limiters.login');
    $twoFactorLimiter = config('fortify.limiters.two-factor');
    $verificationLimiter = config('fortify.limiters.verification', '6,1');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:' . config('fortify.guard'),
            $limiter ? 'throttle:' . $limiter : null,
        ]));

    Route::post('logout', [UserController::class, 'logout'])->name('logout');

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        if ($enableViews) {
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('password.request');

            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('password.reset');
        }

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest:' . config('fortify.guard')])
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware(['guest:' . config('fortify.guard')])
            ->name('password.update');
    }

    // Registration...
    if (Features::enabled(Features::registration())) {
        if ($enableViews) {
            Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('register');
        }

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest:' . config('fortify.guard')]);
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        if ($enableViews) {
            Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
                ->name('verification.notice');
        }

        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'signed', 'throttle:' . $verificationLimiter])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'throttle:' . $verificationLimiter])
            ->name('verification.send');
    }

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
            ->name('user-profile-information.update');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
            ->name('user-password.update');
    }

    // Password Confirmation...
    if ($enableViews) {
        Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')]);
    }

    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('password.confirmation');

    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('password.confirm');

    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        if ($enableViews) {
            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                ->middleware(['guest:' . config('fortify.guard')])
                ->name('two-factor.login');
        }

        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest:' . config('fortify.guard'),
                $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
            ]));

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'password.confirm']
            : [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')];

        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.enable');

        Route::post('/user/confirmed-two-factor-authentication', [ConfirmedTwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.confirm');

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.disable');

        Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.qr-code');

        Route::get('/user/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.secret-key');

        Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.recovery-codes');

        Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }
});
