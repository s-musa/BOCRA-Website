<?php

namespace App\Http\Middleware;

use App\Models\Ecommerce\EcmShop;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
//            'messages' => flash()->render('array'),
            'logoUrl' => function () {
                $company = \App\Models\Company::firstOrFail() ?? null;
                
                return $company->hasMedia('logo') ? $company->getMedia('logo')->sortByDesc('created_at')->first()->getUrl() : null;
            },
            'faviconUrl' => function () {
                $company = \App\Models\Company::firstOrFail() ?? null;
                
                return $company->hasMedia('favicon') ? $company->getMedia('favicon')->sortByDesc('created_at')->first()->getUrl() : null;
            },
            'company' => function () {
                $company = \App\Models\Company::firstOrFail();
                $company->load('media');
                
                return $company;
            },
            'auth.user' => function () use ($request) {
                $user = Auth::guard('web')->user();
                
                return $user
                    ? [
                        'id' => $user->hashid,
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'username' => $user->username,
                        'roles' => $user->roles->pluck('name'),
                        'permissions' => $user->permissions->pluck('name'),
                    ]
                    : null;
            },
            'auth.customer' => function () use ($request) {
                $customer = Auth::guard('customer')->user();
                
                return $customer
                    ? [
                        'id' => $customer->hashid,
                        'customer_id' => $customer->id,
                        'name' => $customer->name,
                        'username' => $customer->username,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                    ]
                    : null;
            },
            'modules' => function () {
                return \App\Models\Module::installedAndEnabled()->get();
            },
            'allSettings' => function () {
                $settings = \App\Models\Setting::getSettings();
                
                return $settings ?? null;
            },
            'shop' => function () use ($request) {
                $shop = [];
                
                if(Schema::hasTable('ecm_shops')) {
                    $shop = EcmShop::first() ?? new EcmShop();
                }
                
                return $shop
                    ? [
                        'id' => $shop->hashid,
                        'shop_id' => $shop->id,
                        'name' => $shop->name,
                        'email' => $shop->email,
                        'phone' => $shop->phone,
                        'currency' => $shop->currency,
                    ]
                    : null;
            },
        ]);
    }
}
