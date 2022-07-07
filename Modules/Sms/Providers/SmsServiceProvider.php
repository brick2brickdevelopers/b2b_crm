<?php

namespace Modules\Sms\Providers;

use App\Events\EventInviteEvent;
use App\Events\InvoicePaymentEvent;
use App\Events\LeadEvent;
use App\Events\LeaveEvent;
use App\Events\NewExpenseEvent;
use App\Events\NewInvoiceEvent;
use App\Events\NewNoticeEvent;
use App\Events\NewProjectMemberEvent;
use App\Events\ProductPurchaseEvent;
use App\Events\ProjectReminderEvent;
use App\Events\TaskEvent;
use App\Events\TaskReminderEvent;
use App\Events\TicketEvent;
use App\Listeners\LeadListener;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Event;
use Modules\Sms\Entities\SmsSetting;
use Modules\Sms\Listeners\SmsEventInviteListener;
use Modules\Sms\Listeners\SmsInvoiceListener;
use Modules\Sms\Listeners\SmsInvoicePaymentListener;
use Modules\Sms\Listeners\SmsLeadListener;
use Modules\Sms\Listeners\SmsLeaveListener;
use Modules\Sms\Listeners\SmsNewExpenseListener;
use Modules\Sms\Listeners\SmsNewNoticeListener;
use Modules\Sms\Listeners\SmsNewProjectMemberListener;
use Modules\Sms\Listeners\SmsProductPurchaseListener;
use Modules\Sms\Listeners\SmsProjectReminderListener;
use Modules\Sms\Listeners\SmsTaskListener;
use Modules\Sms\Listeners\SmsTaskReminderListener;
use Modules\Sms\Listeners\SmsTicketListener;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Event::listen(TaskEvent::class, SmsTaskListener::class);
        Event::listen(NewInvoiceEvent::class, SmsInvoiceListener::class);
        Event::listen(LeaveEvent::class, SmsLeaveListener::class);
        Event::listen(NewExpenseEvent::class, SmsNewExpenseListener::class);
        Event::listen(NewProjectMemberEvent::class, SmsNewProjectMemberListener::class);
        Event::listen(NewNoticeEvent::class, SmsNewNoticeListener::class);
        Event::listen(TicketEvent::class, SmsTicketListener::class);
        Event::listen(LeadEvent::class, SmsLeadListener::class);
        Event::listen(InvoicePaymentEvent::class, SmsInvoicePaymentListener::class);
        Event::listen(ProductPurchaseEvent::class, SmsProductPurchaseListener::class);
        Event::listen(TaskReminderEvent::class, SmsTaskReminderListener::class);
        Event::listen(ProjectReminderEvent::class, SmsProjectReminderListener::class);
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('sms.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'sms'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/sms');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/sms';
        }, \Config::get('view.paths')), [$sourcePath]), 'sms');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/sms');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'sms');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'sms');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
