<?php

namespace App\Providers;

use App\Services\CartService;
use App\Services\MailSenderService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\CustomMenu\app\Enums\DefaultMenusEnum;
use Modules\GlobalSetting\app\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('wsuscart', function ($app) {
            return new CartService();
        });

        $this->app->singleton('wsusmailsender', function ($app) {
            return new MailSenderService();
        });
    }

    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

        try {
            $setting = Cache::rememberForever('setting', function () {
                return (object) Setting::select('key', 'value')->get()
                    ->pluck('value', 'key')
                    ->toArray();
            });

            $this->setupMailConfiguration($setting);
            $this->setupTimezone($setting);
            $this->shareViewData($setting);

        } catch (Exception $ex) {

            logError('Error in AppServiceProvider: ' . $ex->getMessage(), $ex);

            if (strtolower(config('app.app_mode')) == 'live' && !app()->isLocal()) {
                Artisan::call('optimize:clear');
                http_response_code(500);
                echo view('errors.init-failed', [
                    'error' => $ex->getMessage()
                ])->render();
                exit;
            }
        }

        $this->registerBladeDirectives();

        Paginator::useBootstrapFour();

        $this->setPaginationForCollection();

        view()->share('nonce', base64_encode(random_bytes(16)));

        $this->loadViewsFrom(resource_path('views/website/components'), 'components');

        // ❌ Seller views removed
        // $this->loadViewsFrom(resource_path('views/seller'), 'vendor');
    }

    protected function setupMailConfiguration($setting): void
    {
        $mailConfig = [
            'transport'  => 'smtp',
            'host'       => $setting?->mail_host,
            'port'       => $setting?->mail_port,
            'encryption' => $setting?->mail_encryption,
            'username'   => $setting?->mail_username,
            'password'   => $setting?->mail_password,
            'timeout'    => null,
        ];

        config(['mail.mailers.smtp' => $mailConfig]);
        config(['mail.from.address' => $setting?->mail_sender_email]);
        config(['mail.from.name' => $setting?->mail_sender_name]);
    }

    protected function setupTimezone($setting): void
    {
        config(['app.timezone' => $setting?->timezone]);
    }

    protected function setPaginationForCollection(): void
    {
        Collection::macro('paginate', function ($perPage = 16, $total = null, $page = null, $pageName = 'page'): LengthAwarePaginator {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path'     => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    protected function registerBladeDirectives(): void
    {
        // ✅ ADMIN
        Blade::directive('adminCan', function ($permission) {
            return "<?php 
                \$adminUser = auth()->guard('admin')->user();
                if(\$adminUser && method_exists(\$adminUser, 'can') && \$adminUser->can({$permission})):
            ?>";
        });

        Blade::directive('endadminCan', fn() => '<?php endif; ?>');

        // ✅ STAFF
        Blade::directive('staffCan', function ($permission) {
            return "<?php 
                \$staffUser = auth()->guard('staff')->user();
                if(\$staffUser && method_exists(\$staffUser, 'can') && \$staffUser->can({$permission})):
            ?>";
        });

        Blade::directive('endstaffCan', fn() => '<?php endif; ?>');

        // ✅ GENERIC CAN
        Blade::directive('can', function ($permission) {
            return "<?php 
                \$user = auth()->user() ?? auth()->guard('staff')->user() ?? auth()->guard('admin')->user();
                if(\$user && method_exists(\$user, 'can') && \$user->can({$permission})):
            ?>";
        });

        Blade::directive('endcan', fn() => '<?php endif; ?>');

        // ✅ AUTH CHECK
        Blade::directive('authcheck', function ($guard = 'staff') {
            return "<?php if(auth()->guard({$guard})->check()): ?>";
        });

        Blade::directive('endauthcheck', fn() => '<?php endif; ?>');
    }

    public function shareViewData($setting): void
    {
        try {
            $defaultMenus = DefaultMenusEnum::class;

            config([
                'custom.admin_login_prefix' => $setting->admin_login_prefix ?? 'admin',
            ]);

            View::share('setting', $setting);
            View::share('defaultMenus', $defaultMenus);

            // ✅ STAFF
            View::composer('*', function ($view) {
                $staffUser = auth()->guard('staff')->user();
                $view->with('staffUser', $staffUser);
                $view->with('isStaffLoggedIn', !is_null($staffUser));
            });

            // ✅ ADMIN
            View::composer('*', function ($view) {
                $adminUser = auth()->guard('admin')->user();
                $view->with('adminUser', $adminUser);
                $view->with('isAdminLoggedIn', !is_null($adminUser));
            });

        } catch (Exception $e) {
            logError("Error in ViewDataService::shareViewData: ", $e);
            abort(500, $e->getMessage());
        }
    }
}