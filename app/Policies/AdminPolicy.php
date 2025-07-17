<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\AdminFinancial;
use App\Models\Agency;
use App\Models\AgencyFinancial;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\City;
use App\Models\Driver;
use App\Models\DrZantia\PreOrder;
use App\Models\Order;
use App\Models\Pack;
use App\Models\Product;
use App\Models\Repository;
use App\Models\RepositoryOrder;
use App\Models\Role;
use App\Models\Sample;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\ShippingMethod;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserFinancial;
use App\Models\Variation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class AdminPolicy
{
    use HandlesAuthorization;


    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(Admin $admin, $ability)
    {

        if ($admin->role == 'god') {
            return true;
        }

    }

    public function editAny(Admin $admin, $item, $abort = true, $option = null)
    {
        if (!$item) {
            $message = __("item_not_found");

        }
        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }

        if ($item && $item->status == 'block') {
            $message = __("item_is_blocked");
        }
        if (empty($message) && $item)
            switch ($item) {
                case  $item instanceof Agency  :
                    $res = $admin->hasAccess('agency:edit');
                    if ($res) {
                        $myAgency = Agency::find($admin->agency_id);
                        if (!$myAgency)
                            $res = false;
                        elseif ($myAgency->level == '1')
                            $res = $myAgency->id == $item->id || in_array($myAgency->province_id, $item->access ?? []);
                        elseif ($myAgency->level == '2')
                            $res = $myAgency->id == $item->id || in_array($myAgency->id, $item->access ?? []);
                        elseif ($myAgency->level == '3')
                            $res = $myAgency->id == $item->id;

                    }

                    break;
                case  $item instanceof Repository  :
                    $res = $admin->hasAccess('repository:edit:*');

                    break;
                case  $item instanceof ShippingMethod  :
                    $res = $admin->hasAccess('shipping-method:edit:*');
                    break;

                case  $item instanceof Pack  :
                    $res = $admin->hasAccess('pack:edit:*');
                    break;
                case   $item instanceof Product :
                    $res = $admin->hasAccess('product:edit:*');
                    break;
                case   $item instanceof Variation :
                    $res = $admin->hasAccess('variation:edit:*');
                    break;
                case   $item instanceof Sample :
                    $res = $admin->hasAccess('sample:edit:*');
                    break;
                case   $item instanceof RepositoryOrder :
                    $res = $admin->hasAccess('repository_order:edit:*');
                    if ($res)
                        break;
                    $agencyIds = $admin->allowedAgencies(Agency::find($admin->agency_id))->pluck('id');
                    $res = in_array($item->from_agency_id, $agencyIds->toArray());
                    break;
                case   $item instanceof Driver :
                    $res = $admin->hasAccess('driver:edit:*');
                    break;
                case   $item instanceof Car :
                    $res = $admin->hasAccess('car:edit:*');
                    break;
                case   $item instanceof Order :
                    $res = $admin->hasAccess('order:edit:*');
                    break;
                case   $item instanceof PreOrder :
                    $res = $admin->hasAccess('order:edit:*');
                    break;
                case   $item instanceof Shipping :
                    $res = $admin->hasAccess('shipping:edit:*');
                    break;
                case   $item instanceof Ticket :
                    $res = $admin->hasAccess('ticket:edit:*');
                    break;
                case   $item instanceof User :
                    $res = $admin->hasAccess('user:edit:*');
                    break;
                case   $item instanceof Admin :
                    $res = $admin->hasAccess('admin:edit:*');
                    break;
                case   $item instanceof Setting :
                    $res = $admin->hasAccess('setting:edit:*');
                    break;
                case   $item instanceof UserFinancial :
                case   $item instanceof AdminFinancial :
                case   $item instanceof AgencyFinancial :
                    $res = $admin->hasAccess('financial:edit:*');
                    break;
                case   $item instanceof Catalog :
                    $res = $admin->hasAccess('catalog:edit:*');
                    break;
                case   $item instanceof Article :
                    $res = $admin->hasAccess('article:edit:*');
                    break;
                case   $item instanceof Category :
                    $res = $admin->hasAccess('category:edit:*');
                    break;
                case   $item instanceof Brand :
                    $res = $admin->hasAccess('brand:edit:*');
                    break;
                case   $item instanceof Role :
                    $res = $admin->hasAccess('role:edit:*');
                    break;
            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;

        return false;

    }

    public function view(Admin $admin, $item, $abort = true, $option = null)
    {


        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }

        if (empty($message) && $item)
            switch ($item) {
                case     Agency::class :
                    $res = $admin->hasAccess('agency:view');
                    break;
                case    Repository::class:
                    $res = $admin->hasAccess('repository:view');
                    break;
                case    ShippingMethod::class:
                    $res = $admin->hasAccess('shipping-method:view');
                    break;
                case    Pack::class:
                    $res = $admin->hasAccess('pack:view');
                    break;
                case    Product::class:
                    $res = $admin->hasAccess('product:view');
                    break;
                case    Variation::class:
                    $res = $admin->hasAccess('variation:view');
                    break;
                case    Sample::class:
                    $res = $admin->hasAccess('sample:view');
                    break;
                case    User::class:
                    $res = $admin->hasAccess('user:view');
                    break;
                case    Catalog::class:
                    $res = $admin->hasAccess('catalog:view');
                    break;
                case
                Brand::class:
                    $res = $admin->hasAccess('brand:view');
                    break;
                case
                Role::class:
                    $res = $admin->hasAccess('role:view');
                    break;
                case   Category::class:
                    $res = $admin->hasAccess('category:view');
                    break;
                case   Admin::class:
                    $res = $admin->hasAccess('admin:view');
                    break;
            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;
        return false;
    }

    public function create(Admin $admin, $item, $abort = true, $option = null)
    {

        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }
        if (empty($message) && $item)
            switch ($item) {
                case     Agency::class :
                    $res = $admin->hasAccess('agency:create');
                    break;
                case    Repository::class:
                    $res = $admin->hasAccess('repository:create');
                    break;
                case    ShippingMethod::class:
                    $res = $admin->hasAccess('shipping-method:create');
                    break;
                case    Pack::class:
                    $res = $admin->hasAccess('pack:create');
                    break;
                case    Product::class:
                    $res = $admin->hasAccess('product:create');
                    break;
                case    RepositoryOrder::class:
                    $res = $admin->hasAccess('repository_order:create');
                    break;
                case    Variation::class:
                    $res = $admin->hasAccess('variation:create');
                    break;
                case    Sample::class:
                    $res = $admin->hasAccess('sample:create');
                    break;
                case    Driver::class:
                    $res = $admin->hasAccess('driver:create');
                    break;
                case    Car::class:
                    $res = $admin->hasAccess('car:create');
                    break;
                case    Shipping::class:
                    $res = $admin->hasAccess('shipping:create');
                    break;
                case    Ticket::class:
                    $res = $admin->hasAccess('ticket:create');
                    break;
                case    Admin::class:
                    $res = $admin->hasAccess('admin:create');
                    break;
                case    Category::class:
                    $res = $admin->hasAccess('category:create');
                    break;
                case    Catalog::class:
                    $res = $admin->hasAccess('catalog:create');
                    break;
                case    Article::class:
                    $res = $admin->hasAccess('article:create');
                case    Setting::class:
                    $res = $admin->hasAccess('setting:create');
                    break;
                case    Brand::class:
                    $res = $admin->hasAccess('brand:create');
                    break;
                case    Role::class:
                    $res = $admin->hasAccess('role:create');
                    break;

            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;
        return false;
    }

    public function edit(Admin $admin, $item, $abort = true, $option = null)
    {
        if (!$item) {
            $message = __("item_not_found");

        }
        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }

        if ($item && $item->status == 'block') {
            $message = __("item_is_blocked");
        }
        if (empty($message) && $item)
            switch ($item) {
                case  $item instanceof Agency  :
                    $res = $admin->hasAccess('agency:edit');
                    if ($res) {
                        $myAgency = Agency::find($admin->agency_id);
                        if (!$myAgency)
                            $res = false;
                        elseif ($myAgency->level == '1')
                            $res = $myAgency->id == $item->id || in_array($myAgency->province_id, $item->access ?? []);
                        elseif ($myAgency->level == '2')
                            $res = $myAgency->id == $item->id || in_array($myAgency->id, $item->access ?? []);
                        elseif ($myAgency->level == '3')
                            $res = $myAgency->id == $item->id;

                    }

                    break;
                case  $item instanceof Repository  :
                    $res = $admin->hasAccess('repository:edit');

                    break;
                case  $item instanceof ShippingMethod  :
                    $res = $admin->hasAccess('shipping-method:edit');
                    break;

                case  $item instanceof Pack  :
                    $res = $admin->hasAccess('pack:edit');
                    break;
                case   $item instanceof Product :
                    $res = $admin->hasAccess('product:edit');
                    break;
                case   $item instanceof Variation :
                    $res = $admin->hasAccess('variation:edit');
                    break;
                case   $item instanceof Sample :
                    $res = $admin->hasAccess('sample:edit');
                    break;
                case   $item instanceof RepositoryOrder :
                    $res = $admin->hasAccess('repository_order:edit');
                    if ($res)
                        break;
                    $agencyIds = $admin->allowedAgencies(Agency::find($admin->agency_id))->pluck('id');
                    $res = in_array($item->from_agency_id, $agencyIds->toArray());
                    break;
                case   $item instanceof Driver :
                    $res = $admin->hasAccess('driver:edit');
                    break;
                case   $item instanceof Car :
                    $res = $admin->hasAccess('car:edit');
                    break;
                case   $item instanceof Order :
                    $res = $admin->hasAccess('order:edit');
                    break;
                case   $item instanceof PreOrder :
                    $res = $admin->hasAccess('order:edit');
                    break;
                case   $item instanceof Shipping :
                    $res = $admin->hasAccess('shipping:edit');
                    break;
                case   $item instanceof Ticket :
                    $res = $admin->hasAccess('ticket:edit');
                    break;
                case   $item instanceof User :
                    $res = $admin->hasAccess('user:edit');
                    break;
                case   $item instanceof Admin :
                    $res = $admin->hasAccess('admin:edit');
                    break;
                case   $item instanceof Setting :
                    $res = $admin->hasAccess('setting:edit');
                    break;
                case   $item instanceof UserFinancial :
                case   $item instanceof AdminFinancial :
                case   $item instanceof AgencyFinancial :
                    $res = $admin->hasAccess('financial:edit');
                    break;
                case   $item instanceof Catalog :
                    $res = $admin->hasAccess('catalog:edit');
                    break;
                case   $item instanceof Article :
                    $res = $admin->hasAccess('article:edit');
                    break;
                case   $item instanceof Category :
                    $res = $admin->hasAccess('category:edit');
                    break;
                case   $item instanceof Brand :
                    $res = $admin->hasAccess('brand:edit');
                    break;
                case   $item instanceof Role :
                    $res = $admin->hasAccess('role:edit');
                    break;
            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;

        return false;
    }

    public function delete(Admin $admin, $item, $abort = true, $option = null)
    {
        if (!$item) {
            $message = __("item_not_found");

        }
        if ($admin->status == 'inactive') {
            $message = __("user_is_inactive");

        }
        if ($admin->status == 'block') {
            $message = __("user_is_blocked");
        }

        if ($item && $item->status == 'block') {
            $message = __("item_is_blocked");
        }
        if (empty($message) && $item)
            switch ($item) {
                case  $item instanceof Agency  :
                    $res = $admin->hasAccess('agency:delete');
                    if ($res) {
                        $myAgency = Agency::find($admin->agency_id);
                        if (!$myAgency)
                            $res = false;
                        elseif ($myAgency->level == '1')
                            $res = $myAgency->id == $item->id || in_array($myAgency->province_id, $item->access ?? []);
                        elseif ($myAgency->level == '2')
                            $res = $myAgency->id == $item->id || in_array($myAgency->id, $item->access ?? []);
                        elseif ($myAgency->level == '3')
                            $res = $myAgency->id == $item->id;

                    }

                    break;
                case  $item instanceof Repository  :
                    $res = $admin->hasAccess('repository:delete');

                    break;
                case  $item instanceof ShippingMethod  :
                    $res = $admin->hasAccess('shipping-method:delete');
                    break;

                case  $item instanceof Pack  :
                    $res = $admin->hasAccess('pack:delete');
                    break;
                case   $item instanceof Product :
                    $res = $admin->hasAccess('product:delete');
                    break;
                case   $item instanceof Variation :
                    $res = $admin->hasAccess('variation:delete');
                    break;
                case   $item instanceof Sample :
                    $res = $admin->hasAccess('sample:delete');
                    break;
                case   $item instanceof RepositoryOrder :
                    $res = $admin->hasAccess('repository_order:delete');
                    if ($res)
                        break;
                    $agencyIds = $admin->allowedAgencies(Agency::find($admin->agency_id))->pluck('id');
                    $res = in_array($item->from_agency_id, $agencyIds->toArray());
                    break;
                case   $item instanceof Driver :
                    $res = $admin->hasAccess('driver:delete');
                    break;
                case   $item instanceof Car :
                    $res = $admin->hasAccess('car:delete');
                    break;
                case   $item instanceof Order :
                    $res = $admin->hasAccess('order:delete');
                    break;

                case   $item instanceof Shipping :
                    $res = $admin->hasAccess('shipping:delete');
                    break;
                case   $item instanceof Ticket :
                    $res = $admin->hasAccess('ticket:delete');
                    break;
                case   $item instanceof User :
                    $res = $admin->hasAccess('user:delete');
                    break;
                case   $item instanceof Admin :
                    $res = $admin->hasAccess('admin:delete');
                    break;
                case   $item instanceof Setting :
                    $res = $admin->hasAccess('setting:delete');
                    break;
                case   $item instanceof UserFinancial :
                case   $item instanceof AdminFinancial :
                case   $item instanceof AgencyFinancial :
                    $res = $admin->hasAccess('financial:delete');
                    break;
                case   $item instanceof Catalog :
                    $res = $admin->hasAccess('catalog:delete');
                    break;
                case   $item instanceof Article :
                    $res = $admin->hasAccess('article:delete');
                    break;
                case   $item instanceof Category :
                    $res = $admin->hasAccess('category:delete');
                    break;
                case   $item instanceof Brand :
                    $res = $admin->hasAccess('brand:delete');
                    break;
                case   $item instanceof Role :
                    $res = $admin->hasAccess('access:delete');
                    break;
            }

        if ($abort && empty($res))
            return abort(403, $message ?? __("access_denied"));
        if (!empty($res))
            return true;

        return false;
    }
}
