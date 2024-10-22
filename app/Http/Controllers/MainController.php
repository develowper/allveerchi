<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SMSHelper;
use App\Http\Helpers\Util;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MainController extends Controller
{
    public function main(Request $request)
    {
        if ($r = $request->ref) {
            session(['ref' => $r]);
        }
//    Telegram::log(null, 'order_created', \App\Models\Order::with('items')->with('agency')->orderBy('id', 'DESC')->first());

        if (str_contains(url()->current(), '.ae') || str_contains(url()->current(), 'localhost'))

            return Inertia::render('MainAE', [
                'heroText' => \App\Models\Setting::getValue('hero_main_page'),
                'slides' => \App\Models\Slider::where('is_active', true)->get(),
                'articles' => \App\Models\Article::where('status', 'active')->orderBy('id', 'desc')->take(12)->get(),
                'section1Header' => __('our_services'),
                'sections' =>
                    __('sections_ae')
                ,
                'section2Header' => __('our_benefits'),
                'section2' => [
                ],
                'carouselImages' => [],
                'counts' => [
                    'users' => ['icon' => 'UsersIcon', 'count' => User::count()],
                    'articles' => ['icon' => 'PencilIcon', 'count' => Article::count()],
                ]
            ]);
        return Inertia::render('Main', [
            'heroText' => \App\Models\Setting::getValue('hero_main_page'),
            'slides' => \App\Models\Slider::where('is_active', true)->get(),
            'articles' => \App\Models\Article::where('status', 'active')->orderBy('id', 'desc')->take(12)->get(),
            'section1Header' => __('our_services'),
            'section1' => [
                ['header' => 'تحویل در محل', 'sub' => 'سفارش خود را در سریعترین زمان در محل تحویل بگیرید', 'icon' => 'TruckIcon'],
                ['header' => 'قیمت به صرفه', 'sub' => 'با حذف واسطه ها، حمل و نقل هوشمند و انبارهای اختصاصی، قیمت تمام شده کالا را به حداقل می رسانیم', 'icon' => 'RocketLaunchIcon'],
                ['header' => 'پاسخگویی شبانه روزی', 'sub' => 'در هر زمان از شبانه روز پاسخگوی شما خوهیم بود', 'icon' => 'UsersIcon'],
                ['header' => 'همکاری در فروش', 'sub' => 'با ثبت محصولات شما در فروشگاه و مشاوره تخصصی، به افزایش درامد شما کمک می کنیم', 'icon' => 'MapPinIcon'],
            ],
            'section2Header' => __('our_benefits'),
            'section2' => [
            ],
            'carouselImages' => [],
            'counts' => [
                'users' => ['icon' => 'UsersIcon', 'count' => User::count()],
                'articles' => ['icon' => 'PencilIcon', 'count' => Article::count()],
            ]
        ]);
    }

    public function sendSms(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'numeric', 'digits:11', 'regex:/^09[0-9]+$/'/*, Rule::unique('users', 'phone')->ignore($this->id)*/],
        ], [
            'phone.required' => sprintf(__("validator.required"), __('phone')),
            'phone.unique' => sprintf(__("validator.unique"), __('phone')),
            'phone.regex' => sprintf(__("validator.invalid"), __('phone')),
            'phone.numeric' => sprintf(__("validator.numeric"), __('phone')),
            'phone.digits' => sprintf(__("validator.digits"), __('phone'), 11),

        ]);

        if (SMSHelper::checkRepeatedSMS("$request->phone", 5))
            return response(['message' => sprintf(__('you_received_sms_in_n_minutes'), 5),], 401);
        $code = Util::generateRandomNumber(5);
        $res = (new SMSHelper())->send("$request->phone", "$code", $request->type);
        if ($res) {
            SMSHelper::addCode($request->phone, $code);
            return response(['message' => __('sms_send_to_phone')]);
        }
        return response(['message' => __('sms_send_to_phone')]);
    }

    public
    function makeMoneyPage()
    {

        return Inertia::render('MakeMoney', [
        ]);

    }

    public
    function contactUsPage()
    {

        return Inertia::render('ContactUs', [
        ]);

    }
}
