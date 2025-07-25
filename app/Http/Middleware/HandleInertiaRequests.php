<?php

namespace App\Http\Middleware;

use App\Http\Helpers\Variable;
use App\Models\Role;
use App\Models\Admin;
use App\Models\AdminFinancial;
use App\Models\Agency;
use App\Models\AgencyFinancial;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\City;
use App\Models\Pack;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserFinancial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

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
    public function version(Request $request): string|null
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
        $domainCountry = explode('.', url()->current())[count(explode('.', url()->current())) - 1];
        $socials = Setting::where('key', 'like', 'social_%')->get();
        $user = auth('sanctum')->user();
        if ($user) {
            $user->setRelation('financial', $user instanceof Admin ? AdminFinancial::whereAdminId($user->id)->firstOrNew() : UserFinancial::whereUserId($user->id)->firstOrNew());
            $user->setRelation('role', $user instanceof Admin ? Role::whereId($user->role_id)->where('agency_level', '>=', $user->agency_level)->firstOrNew() : new Role());
            if ($user instanceof Admin) {
                $agency = Agency::with('financial')->findOrNew($user->agency_id);
                if (!$agency->getRelation('financial'))
                    $agency->setRelation('financial', new AgencyFinancial());
            }
        }
        Variable::$CITIES = City::orderby('name')->get();
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
            ],
            'ip' => $request->ip(),
            'admin_roles' => $user && $user instanceof Admin ? Role::select('id', 'name', 'agency_level', 'accesses')->where('agency_level', '>=', $user->agency_level ?? '2')->get() : [],
            'accesses' => $user && $user instanceof Admin ? $user->accesses() : [],
            'isAdmin' => $user && $user instanceof Admin,
            'agency' => $user && $user instanceof Admin ? Agency::with('financial')->find($user->agency_id) : (object)[],
            'agency_types' => Variable::AGENCY_TYPES,
            'location' => $request->url(),
//            'user' => optional(auth()->user())->only(['id', 'fullname', 'username',]),
            'locale' => function () {
                return app()->getLocale();
            },
            'head_notification' => Setting::getValue('head_notification'),
            'slider' => collect(json_decode(Setting::getValue('slider') ?? '[]') ?? [])->map(function ($item) {
                $item->image = route('storage.slides') . "/" . ($item->id ?? '') . ".jpg";
                return $item;
            }),
            'statuses' => Variable::STATUSES,
            'categories' => \App\Models\Category::get(),
            'langs' => Variable::LANGS,
            'default_timestamps' => Variable::TIMESTAMPS,
            'hours' => collect(range(1, 24))->map(fn($e) => ['id' => $e, 'name' => "$e:00"]),
            'images' => asset('assets/images') . '/',
            'language' => function () {
                if (!file_exists(lang_path('/' . app()->getLocale() . '.json'))) {
                    return [];
                }
                return json_decode(file_get_contents(
                        lang_path(
                            app()->getLocale() . '.json'))
                    , true);
            },
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => fn() => $request->session()->get('flash_message'),
                'status' => fn() => $request->session()->get('flash_status'),
            ],
            'extra' => fn() => $request->session()->get('extra'),
            'pageItems' => Variable::PAGINATE,
            'cart' => Cart::getData(),
            'cities' => Variable::$CITIES,
            'is_auction' => Setting::getValue('is_auction'),
            'units' => Variable::PRODUCT_UNITS,
            'packs' => Pack::get(),
            'brands' => Brand::select('id', 'name')->get(),
            'grades' => Variable::GRADES,
            'products' => Product::select('id', 'name')->whereStatus('active')->orderBy('sell_count', 'DESC')->get(),
            'rubikFaces' => $this->getRubicFaces($domainCountry),
            'user_location' => User::getLocation(Variable::$CITIES),
            'socials' => [
                'whatsapp' => optional($socials->where('key', "social_whatsapp_$domainCountry")->first() ?? $socials->where('key', 'social_whatsapp')->first())->value,
                'telegram' => "https://t.me/" . optional($socials->where('key', 'social_telegram')->first())->value,
                'phone' => optional($socials->where('key', "social_phone_$domainCountry")->first() ?? $socials->where('key', 'social_phone')->first())->value,
                'email' => optional($socials->where('key', 'social_email')->first())->value,
                'address' => optional($socials->where('key', 'social_address')->first())->value,
            ],
        ]);
    }

    private function getRubicFaces(string $domainCountry)
    {
        $arr = [];
//        Storage::path("public/$type/$id/" . basename($request->path));
        $faces = DB::table('rubik')->get();
        for ($i = 1; $i <= 54; $i++) {
            $item = $faces->where('face_id', $i)->where('lang', $domainCountry)->first() ?? $faces->where('face_id', $i)->first();
            $arr[] = [
                'face_id' => $item->face_id,
                'title' => $item->title,
                'icon' => $item->icon,
                'link' => $item->link
            ];

        }
        return $arr;
    }

}
