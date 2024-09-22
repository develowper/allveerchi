<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SMSHelper;
use App\Http\Helpers\Telegram;
use App\Http\Helpers\Variable;
use App\Http\Requests\GuaranteeRequest;
use App\Models\Admin;
use App\Models\AdminFinancial;
use App\Models\Agency;
use App\Models\AgencyFinancial;
use App\Models\Car;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Morilog\Jalali\Jalalian;

class GuaranteeController extends Controller
{
    //
    public function create(GuaranteeRequest $request)
    {

        $agency = $request->agency;
        $phone = $request->phone;
        $operator = $request->operator;
        $operatorId = $request->operator_id;
        $barcode = $request->guarantee_code;
        $id = substr($barcode, 0, strlen($barcode) - 12);
        $variation = Variation::find($id);
        if (!$variation || !$variation->guarantee_months)
            return back()->withErrors(['message' => [sprintf(__('*_not_found'), __('product'))]]);
        if ($variation->guarantee_expires_at)
            return back()->withErrors(['message' => [__('guarantee_registered_before')]]);
        $customer = User::where('phone', $phone)->first();
        if (!$customer) {
            $customer = User::create([
                'phone' => $phone,
                'fullname' => "U$phone",
                'phone_verified' => true,
                'ref_id' => User::makeRefCode($phone),
            ]);
        }
        $guaranteeMonths = $variation->guarantee_months;
        $variation->guarantee_expires_at = Carbon::now()->addMonths($guaranteeMonths);
        $variation->operator_id = $operatorId;
        $variation->customer_id = $customer->id;
        $variation->save();
        $variation->operator = $operator;
        $variation->agency = $agency;
        $variation->guarantee_expires_at_shamsi = Jalalian::fromCarbon($variation->guarantee_expires_at)->format('Y/m/d');
        Telegram::log(null, 'guarantee_created', $variation);
        $product = Product::find($variation->product_id);
        //add operator percent
        if ($operator && $agency && $product) {
            $smsHelper = new SMSHelper();
//            SMSHelper::deleteCode($phone);
//            $smsHelper->send($phone, "$barcode\$$variation->guarantee_expires_at_shamsi", 'guarantee_started');
            $percent = Setting::getValue('operator_profit_percent') ?? 0;
            if ($percent > 0) {
                $t = Transaction::create([
                    'title' => sprintf(__('profit_operator_agency_*_*_*'), floor($percent), $variation->id, "$operator->fullname($operator->id)"),
                    'type' => "profit",
                    'for_type' => 'operator',
                    'for_id' => $operatorId,
                    'from_type' => 'agency',
                    'from_id' => 1,
                    'to_type' => 'operator',
                    'to_id' => $operatorId,
                    'is_success' => true,
                    'info' => null,
                    'coupon' => null,
                    'payed_at' => Carbon::now(),
                    'amount' => floor($percent / 100 * (Setting::getValue('is_auction') && $product->auction_price !== null ? $product->auction_price : $product->price)),
                    'pay_id' => null,
                ]);

                $agencyF = AdminFinancial::firstOrNew(['admin_id' => $operatorId]);
                $agencyF->wallet += $t->amount;
                $agencyF->save();

                $t->user = $operator;
                Telegram::log(null, 'transaction_created', $t);
            }
        }
//        Telegram::log(null, 'guarantee_created', $variation);
        $res = ['flash_status' => 'success', 'flash_message' => __('done_successfully')];
        return to_route('admin.panel.guarantee.index')->with($res);
    }

    protected
    function searchPanel(Request $request)
    {
        $admin = $request->user();

        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;
        $status = $request->status;
        $grade = $request->grade;

        $query = Variation::query()->whereNotNull('guarantee_expires_at')->select();
        $query->whereIntegerInRaw('agency_id', $admin->allowedAgencies(Agency::find($admin->agency_id))->pluck('id'));
        if ($search)
            $query = $query->where('name', 'like', "%$search%");
        if ($status)
            $query = $query->where('status', $status);
        if ($grade)
            $query = $query->where('grade', $grade);

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);


    }
}
