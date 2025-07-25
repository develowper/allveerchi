<?php

namespace App\Http\Helpers;

use App\Models\Admin;
use App\Models\AdminFinancial;
use App\Models\Agency;
use App\Models\AgencyFinancial;
use App\Models\Article;
use App\Models\ArticleTransaction;
use App\Models\Banner;
use App\Models\BannerTransaction;
use App\Models\Brand;
use App\Models\Business;
use App\Models\BusinessTransaction;
use App\Models\Car;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\City;
use App\Models\Driver;
use App\Models\DrZantia\PreOrder;
use App\Models\Message;
use App\Models\Order;
use App\Models\Podcast;
use App\Models\PodcastTransaction;
use App\Models\Product;
use App\Models\RepositoryOrder;
use App\Models\Setting;
use App\Models\Site;
use App\Models\SiteTransaction;
use App\Models\Slider;
use App\Models\Text;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserFinancial;
use App\Models\Variation;
use App\Models\Video;
use App\Models\VideoTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Variable
{


    const LANGS = ['fa', 'en', 'ar'];
    const SMS_SENDER = 'sms.ir';

    const MARKETS = ['bazaar', 'myket', 'playstore', 'site'];
    const GATEWAYS = ['bazaar', 'myket', 'nextpay'];

    const USER_ROLES = ['us'];
    const ADMIN_ROLES = ['god', 'owner', 'admin', 'operator'];
    const AGENCY_TYPES = [
        ['id' => 0, 'name' => 'central', 'level' => '0'],
//        ['id' => 1, 'name' => 'zone_agency', 'level' => '1'],
//        ['id' => 2, 'name' => 'province_agency', 'level' => '2'],
        ['id' => 1, 'name' => 'branch_agency', 'level' => '1'],
//        ['id' => 2, 'name' => 'operator_agency', 'level' => '2'],
//        ['name' => 'branch', 'code' => 4],
    ];
    const PRODUCT_UNITS = ['piece', 'kg'/*, 'gr'*/];

    const ADMIN_ACCESS = ['all'];
    const GRADES = ['1', '2', '3'];
    const PARTNERSHIP_TYPES = [
        ['name' => 'agency', 'color' => 'gray'],
        ['name' => 'farmer', 'color' => 'teal'],
        ['name' => 'gardener', 'color' => 'lemon'],

    ];
    const WALLET_TYPES = ['wallet', 'check_wallet'];

    const  STATUSES = [
        ["name" => 'inactive', "color" => 'gray'],
        ["name" => 'active', "color" => 'success'],
        ["name" => 'block', "color" => 'danger'],
    ];
    const  VARIATION_STATUSES = [
        ["name" => 'active', "color" => 'success'],
        ["name" => 'inactive', "color" => 'gray'],
    ];
    const  SAMPLE_STATUSES = [
//        ["name" => 'shop', "color" => 'success'],
//        ["name" => 'repo', "color" => 'blue'],
//        ["name" => 'sold', "color" => 'gray'],
        ["name" => 'active', "color" => 'success'],
        ["name" => 'inactive', "color" => 'danger'],
    ];
    const  USER_STATUSES = [
        ["name" => 'active', "color" => 'success'],
        ["name" => 'inactive', "color" => 'gray'],
        ["name" => 'block', "color" => 'danger'],
    ];
    const  TICKET_STATUSES = [
        ["name" => 'review', "color" => 'danger'],
        ["name" => 'closed', "color" => 'gray'],
        ["name" => 'responded', "color" => 'success'],
    ];
    const  MESSAGE_STATUSES = [
        ["name" => 'order', "color" => 'teal'],
        ["name" => 'referral', "color" => 'blue'],
    ];
    const  ORDER_STATUSES = [

        ["name" => 'request', "color" => 'violet'],
        ["name" => 'pending', "color" => 'danger'],
        ["name" => 'processing', "color" => 'orange'],
        ["name" => 'ready', "color" => 'green'],
        ["name" => 'sending', "color" => 'blue'],
        ["name" => 'delivered', "color" => 'gray'],
        ["name" => 'canceled', "color" => 'gray'],
        ["name" => 'rejected', "color" => 'gray'],
        ["name" => 'refunded', "color" => 'gray'],
    ];
    const  SHIPPING_STATUSES = [

        ["name" => 'preparing', "color" => 'orange'],
        ["name" => 'sending', "color" => 'blue'],
        ["name" => 'done', "color" => 'success'],
        ["name" => 'canceled', "color" => 'gray'],

    ];
    const CATEGORIES = [
        ['id' => 1, 'name' => 'لوازم خودرو', 'parent_id' => null, 'children' => '[]', 'level' => 1],
        ['id' => 2, 'name' => 'روانکار', 'parent_id' => null, 'children' => "[]", 'level' => 1],


    ];
    const PRICE_TYPES = [
        ['key' => 'cash',],
        ['key' => '1_check',],
        ['key' => '2_check',],
    ];
    const TIMESTAMPS = [
        ['from' => 7, 'to' => 10, 'active' => true],
        ['from' => 10, 'to' => 13, 'active' => true],
        ['from' => 13, 'to' => 16, 'active' => true],
        ['from' => 16, 'to' => 19, 'active' => true],
        ['from' => 7, 'to' => 10, 'active' => true],
        ['from' => 10, 'to' => 13, 'active' => true],
        ['from' => 13, 'to' => 16, 'active' => true],
        ['from' => 16, 'to' => 19, 'active' => true],
    ];

    const SUCCESS_STATUS = 200;
    const ERROR_STATUS = 422;

    const  TRANSACTION_TYPES = ['pay', 'profit', 'settlement', 'charge', 'shipping'];
    const  FINANCIALS = ['admin' => AdminFinancial::class, 'user' => UserFinancial::class, 'agency' => AgencyFinancial::class];
    const  TRANSACTION_MODELS = ['order' => Order::class, 'repo-order' => RepositoryOrder::class, 'admin' => Admin::class, 'user' => User::class, 'agency' => Agency::class, 'operator' => Admin::class, 'pre-order' => PreOrder::class];
    const  PAYER_TYPES = ['admin' => Admin::class, 'operator' => Admin::class, 'user' => User::class, 'agency' => Agency::class];
    const REF_TYPES = ['register',];
    const BANK_GATEWAY = 'nextpay';
    const PAY_TIMEOUT = 1;
    const BANNER_IMAGE_LIMIT_MB = 10;
    const SITE_IMAGE_LIMIT_MB = 4;
    const BUSINESS_IMAGE_LIMIT = 4;
    const TICKET_ATTACHMENT_MAX_LEN = 5;

    const TICKET_ATTACHMENT_ALLOWED_MIMES = ['jpeg', 'jpg', 'png', 'txt', 'pdf'];
    const BANNER_ALLOWED_MIMES = ['jpeg', 'jpg', 'png'];
    const PRODUCT_IMAGE_LIMIT_MB = 50;
    const DRIVER_IMAGE_LIMIT_MB = 10;
    const VARIATION_IMAGE_LIMIT = 5;

    const PRODUCT_ALLOWED_MIMES = ['jpeg', 'jpg', 'png'];
    const DRIVER_ALLOWED_MIMES = ['jpeg', 'jpg', 'png'];


    const MIN_SELL_PRICE = 5000;
    const PODCAST_ALLOWED_MIMES = ['mp3', 'mpga'];
    const VIDEO_ALLOWED_MIMES = ['mp4',];
    const LOGS = [72534783, 1212754313];
    const PAGINATE = [24, 50, 100];
    const IMAGE_FOLDERS = [
        Article::class => 'articles',
        Ticket::class => 'tickets',
        User::class => 'users',
        Admin::class => 'admins',
        Slider::class => 'slides',
        Product::class => 'products',
        Variation::class => 'variations',
        Driver::class => 'drivers',
        Car::class => 'cars',
        Catalog::class => 'catalogs',
        Category::class => 'categories',
        Brand::class => 'brands',
    ];
    const NOTIFICATION_TYPES = [
        "pay",
        "access_change",
        "ticket_answer"
    ];

    const PROJECT_ITEMS = [
        ['name' => 'podcast', 'color' => 'sky',],
        ['name' => 'video', 'color' => 'purple',],
        ['name' => 'banner', 'color' => 'orange',],
        ['name' => 'text', 'color' => 'rose',]

    ];

    const ACCESSES = [
        'category' => ['view', 'create', 'delete', 'edit' => [
            'image',
            'name',
            'level',
            'status',
            'parent_id',
            'children',
        ]
        ],
        'product' => ['view', 'create', 'delete',
            'edit' => [
                'image',
                'name',
                'name_en',
                'PN',
                'categories',
                'tags',
                'status',
                'description',
            ]],
        'variation' => ['view', 'create', 'delete',
            'edit' => [
                'image',
                'name',
                'name_en',
                'tags',
                'status',
                'repo_id',
                'brand_id',
                'product_id',
                'pack_id',
                'weight',
                'in_repo',
                'in_shop',
                'in_auction',
                'price',
                'description',
            ]],
        'admin' => ['view', 'create', 'delete', 'edit' => [
            'image',
            'agency_id',
            'role_id',
            'fullname',
            'phone',
            'status',
            'national_code',
            'card',
            'sheba',
            'wallet',
//            'postal_code',
//            'province_id',
//            'county_id',
//            'district_id',
//            'location',
            'address',
            'password',
        ]
        ],
        'role' => ['view', 'create', 'delete', 'edit' => [
            'name',
            'agency_level',
            'accesses',
        ]
        ],
        'sample' => ['view', 'create', 'delete',
        ],

        'guarantee' => ['view', 'create', 'delete', 'edit' => []
        ],
        'order' => ['view', 'create', 'delete',
            'edit' => [
                'repo_id',
                'shipping_method_id',
//                'province_id',
//                'county_id',
//                'district_id',
//                'receiver_fullname',
//                'receiver_phone',
//                'postal_code',
                'address',
                'status',
                'change_price',
                'products',

            ]],
        'agency' => ['view', 'create', 'delete', 'edit' => [
            'name',
            'phone',
            'level',
            'parent_id',
//            'province_id',
//            'county_id',
//            'district_id',
//            'location',
//            'postal_code',
            'address',
            'status',
            'card',
            'sheba',
            'wallet',
            'order_profit_percent',
        ]
        ],
        'repository' => ['view', 'create', 'delete', 'edit' => [
            'name',
            'is_shop',
            'allow_visit',
            'agency_id',
            'admin_id',
            'phone',
//            'province_id',
//            'county_id',
//            'district_id',
//            'location',
//            'postal_code',
            'address',
            'cities',
            'status',

        ]
        ],
        'shipping_method' => ['view', 'create', 'delete', 'edit' => [
            'name',
            'description',
            'agency_id',
            'repo_id',
            'products',
            'cities',
            'min_order_weight',
            'per_weight_price',
            'per_distance_price',
            'base_price',
            'status',
        ]
        ],


        'brand' => ['view', 'create', 'delete', 'edit' => [
            'image',
            'name',
        ]
        ],
        'pack' => ['view', 'create', 'delete', 'edit' => [
            'name',
            'status',
            'weight',
            'height',
            'width',
            'length',
            'price',
        ]
        ],
        'article' => ['view', 'create', 'delete', 'edit' => [
            'owner_id',
            'author',
            'title',
            'slug',
            'content',
            'status',
        ]
        ],

        'ticket' => ['view', 'create', 'delete', 'edit' => [

        ]
        ],
        'notification' => ['view', 'create', 'delete', 'edit' => [

        ]
        ],
        'setting' => ['view', 'create', 'delete', 'edit' => [
            'value',
        ]
        ],
    ];

    const NOTIFICATION_LIMIT = 5;
    const CITY_ID = null; //61 تجریش
    const RATIOS = ['slider' => 1.8];
    const PACKAGE = 'com.dabel.dabelchin';
    const TELEGRAM_BOT = 'allveerchibot';
    const LINKS = ['bazaar' => '', 'myket' => '', 'playstore' => ''];

    static $CITIES = [];
    public static $BANK = 'zarinpal';

    static function getPaymentMethods()
    {
        return [
            ['name' => __('online_payment'), 'description' => '', 'key' => 'online', 'selected' => true, 'active' => true],
//            ['name' => __('local_payment'), 'description' => '', 'key' => 'local', 'selected' => false, 'active' => true],
            ['name' => __('wallet_payment'), 'description' => '', 'key' => 'wallet', 'selected' => false, 'active' => true],
            ['name' => __('1_check'), 'description' => '', 'key' => '1_check', 'selected' => false, 'active' => true],
            ['name' => __('2_check'), 'description' => '', 'key' => '2_check', 'selected' => false, 'active' => true]
        ];
    }

    static function getAgencies()
    {
        return [

            [
                'id' => 1,
                'name' => 'دفتر مرکزی',
                'phone' => '09122466401',
                'access' => null,
                'parent_id' => null,
                'level' => strval(0),
                'province_id' => City::where('level', 1)->where('name', 'گیلان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'بندر انزلی')->first()->id,
                'address' => 'منظقه آزاد انزلی',
                'status' => 'active',
                'postal_code' => null,
            ], [
                'id' => 2,
                'name' => 'Bigerz Co',
                'phone' => '09122466401',
                'access' => null,
                'parent_id' => 1,
                'level' => strval(1),
                'province_id' => City::where('level', 1)->where('name', 'گیلان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'بندر انزلی')->first()->id,
                'address' => 'Turkey',
                'status' => 'active',
                'postal_code' => null,
            ]

        ];
    }

    static function getRepositories()
    {
        return [

            [
                'id' => 1,
                'name' => 'انبار مرکزی',
                'agency_id' => 1,
                'is_shop' => true,
                'province_id' => City::where('level', 1)->where('name', 'گیلان')->first()->id,
                'county_id' => City::where('level', 2)->where('name', 'بندر انزلی')->first()->id,
                'address' => 'گیلان، شهرستان بندر انزلی، شهرک صنعتی',
                'location' => '37.469254,49.477436',
                'status' => 'active',
                'cities' => json_encode([]),
//                'cities' => json_encode(array_merge(City::where('parent_id', City::where('level', 2)->where('name', 'تهران')->first()->id)->take(20)->inRandomOrder()->pluck('id')->toArray(), [])),
            ],

        ];
    }

    static function getAdmins()
    {
        return [

            ['id' => 1, 'fullname' => 'مدیر مرکزی', 'phone' => '09122466401', 'status' => 'active', 'role' => 'owner', 'agency_id' => 1, 'agency_level' => '0',
                'access' => json_encode(['all']), 'email' => 'eh.shakibi@gmail.com', 'password' => Hash::make(env('ADMIN_PASSWORD')), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ],
            ['id' => 2, 'fullname' => 'Tadavom (Ashori)', 'phone' => '09910397335', 'status' => 'active', 'role' => 'admin', 'agency_id' => 1, 'agency_level' => '0',
                'access' => json_encode(['all']), 'email' => 'Mahdiehashouri77@yahoo.com', 'password' => Hash::make('Tkpc@123456'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ], ['id' => 3, 'fullname' => 'Tadavom (Mahernia)', 'phone' => '09914546648', 'status' => 'active', 'role' => 'admin', 'agency_id' => 1, 'agency_level' => '0',
                'access' => json_encode(['all']), 'email' => 'mobinasayad95@gmail.com', 'password' => Hash::make('Tkpc@123456'), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ],

        ];
    }

    static function getUsers()
    {
        return [

            ['id' => 1, 'fullname' => 'شکیبی', 'phone' => '09122466401', 'status' => 'active', 'ref_id' => 'shakibi',
                'access' => json_encode(['all']), 'email' => 'moj2raj2@gmail.com', 'password' => Hash::make(env('ADMIN_PASSWORD')), 'email_verified_at' => Carbon::now(), 'created_at' => Carbon::now(), 'phone_verified' => true,
            ],

        ];
    }

    static function getSettings()
    {
        return [
            ['key' => 'hero_main_page', 'value' => __('hero_main_page'), "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_telegram', 'value' => 'owlwisdom', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_whatsapp', 'value' => '00989122466401', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_whatsapp_ae', 'value' => '00971559153020', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_email', 'value' => 'eh.shakibi@gmail.com', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_phone', 'value' => '09122466401', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_phone_ae', 'value' => '00971559153020', "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'social_address', 'value' => __('social_address'), "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'is_auction', 'value' => 0, "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'order_reserve_minutes', 'value' => 30, "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'order_percent_level_0', 'value' => 20, "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'order_percent_level_1', 'value' => 80, "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'order_percent_level_2', 'value' => 0, "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'order_percent_level_3', 'value' => 0, "created_at" => \Carbon\Carbon::now(),],
            ['key' => 'operator_profit_percent', 'value' => 20, "created_at" => \Carbon\Carbon::now(),],
            ['key' => '1_check_profit_percent', 'value' => 6, "created_at" => \Carbon\Carbon::now(),],
            ['key' => '2_check_profit_percent', 'value' => 6, "created_at" => \Carbon\Carbon::now(),],

        ];
    }

    public static function getDefaultShippingMethods()
    {
        return [
            [
                'id' => 1,
                'repo_id' => null,
                'products' => null,
                'cities' => null,
                'min_order_weight' => 0,
                'per_weight_price' => 0,
                'base_price' => 0,
                'free_from_price' => null,
                'description' => 'هزینه پست را بعد از دریافت محصول پرداخت کنید',
                'name' => 'پس کرایه',
//                'description' => 'مشتری با مراجعه حضوری، کالای خود را دریافت می نماید',
//                'name' => 'دریافت حضوری از انبار',
            ],

        ];
    }

    public static function getRubikFaces(): array
    {
        for ($i = 1; $i <= 54; $i++) {
            $arr[] = [
                'face_id' => $i,
                'title' => __('shop'),
                'icon' => url('assets/images/logo.png'),
                'link' => url('shop')
            ];
        }
        return $arr;
    }

}
