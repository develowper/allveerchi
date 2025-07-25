<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Agency;
use App\Models\City;
use Illuminate\Validation\Rules\File;
use App\Http\Helpers\Variable;
use App\Models\Business;
use App\Models\Category;
use App\Models\County;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Inertia\Testing\Concerns\Has;
use stdClass;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->myAgency = Agency::find($this->user()->agency_id);
        if (!$this->myAgency)
            abort(403, __("access_denied"));
        if ($this->myAgency->status != 'active')
            abort(403, __("your_agency_inactive"));
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $editMode = (bool)$this->id;
        $admin = $this->user();
        $phoneChanged = true;
        if ($editMode) {
            $data = Admin::find($this->id);
            $this->merge(['data' => $data]);
            $phoneChanged = optional($data)->phone != $this->phone;
            if (!$this->agency_id)
                $this->merge([
                    'agency_id' => optional($data)->agency_id,
                ]);
        }
        $tmp = [];
//        $allowedRoles = array_values(array_filter(Variable::ADMIN_ROLES, function ($e) use ($admin) {
//            if (in_array($admin->role, ['god', 'owner']))
//                return in_array($e, ['owner', 'admin', 'operator']);
//            return in_array($e, ['admin', 'operator']);
//        }));
        $allowedRoles = Role::where('agency_level', '>=', $this->myAgency->id ?? '2')->pluck('id');
        $allowedStatuses = collect(Variable::USER_STATUSES)->pluck('name');
        $user = $this->user();
        $availableAgencies = $user->allowedAgencies($this->myAgency)->get('id', 'name', 'level');
        $selectedAgency = $availableAgencies->where('id', $this->myAgency->level == '3' ? $user->agency_id : $this->agency_id)->first();
        $allowedAgencies = $availableAgencies->pluck('id');
        $regexLocation = "/^[-+]?[0-9]{1,7}(\\.[0-9]+)?,[-+]?[0-9]{1,7}(\\.[0-9]+)?$/";

        if (!$this->cmnd) {
            $this->merge([
                'agency_id' => optional($selectedAgency)->id,
                'agency_level' => optional($selectedAgency)->level,
            ]);
            $tmp = array_merge($tmp, [
                'agency_id' => ['required', Rule::in($allowedAgencies)],

                'fullname' => ['required', 'string', 'max:200'],
                'national_code' => ['nullable', 'numeric'   /*, Rule::unique('drivers', 'national_code')->ignore($this->id)*/],
                'phone' => ['required', 'numeric', 'digits:11', Rule::unique('admins')->where(function ($query) {
//                    $query->where('agency_id', $this->agency_id)
                    $query->where('phone', $this->phone);
                })->ignore($this->id)],
                'phone_verify' => [Rule::requiredIf($phoneChanged), $phoneChanged ? Rule::exists('sms_verify', 'code')->where('phone', $this->phone) : '',],
                'card' => ['nullable', 'numeric', 'digits:16'],
                'sheba' => ['nullable', 'numeric', 'digits:24'],
                'address' => ['nullable', 'max:2048'],
                'province_id' => ['nullable', Rule::in(City::where('level', 1)->pluck('id'))],
                'county_id' => ['nullable', Rule::in(City::where('level', 2)->pluck('id'))],
                'district_id' => ['sometimes', 'nullable', Rule::in(City::where('level', 3)->pluck('id'))],
                'postal_code' => ['nullable', 'max:20'],
                'location' => ['nullable', "regex:$regexLocation",],

            ]);
            if (!$editMode || $this->password)
                $tmp = array_merge($tmp, [
                    'password' => ['required', 'min:6', 'confirmed', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],
                ]);
        }
        if ($this->uploading || $this->img)
            $tmp = array_merge($tmp, [
                'img' => $this->img ? ['nullable', 'base64_image_size:' . Variable::DRIVER_IMAGE_LIMIT_MB * 1024, 'base64_image_mime:' . implode(",", Variable::DRIVER_ALLOWED_MIMES)] : [],
                'agency_id' => ['required', Rule::in($allowedAgencies)],

            ]);
        if ($this->cmnd == 'status' || !$this->cmnd)
            $tmp = array_merge($tmp, [
                'status' => ['required', Rule::in($allowedStatuses)],
                'agency_id' => ['required', Rule::in($allowedAgencies)],
            ]);
        if ($this->cmnd == 'role' || !$this->cmnd)
            $tmp = array_merge($tmp, [
                'role_id' => ['required', Rule::in($allowedRoles)],
                'agency_id' => ['required', Rule::in($allowedAgencies)],
            ]);
        return $tmp;
    }

    public function messages()
    {

        return [


            'agency_id.required' => sprintf(__("validator.required"), __('agency')),
            'agency_id.exists' => sprintf(__("validator.invalid"), __('agency')),

            'fullname.required' => sprintf(__("validator.required"), __('fullname')),
            'fullname.unique' => sprintf(__("validator.unique"), __('name')),
            'fullname.max' => sprintf(__("validator.max_len"), __('name'), 200, mb_strlen($this->fullname)),
            'fullname.string' => sprintf(__("validator.string"), __('fullname')),


            'img.required' => sprintf(__("validator.required"), __('image')),
            'img.base64_image_size' => sprintf(__("validator.max_size"), __("image"), Variable::PRODUCT_IMAGE_LIMIT_MB),
            'img.base64_image_mime' => sprintf(__("validator.invalid_format"), __("image"), implode(",", Variable::PRODUCT_ALLOWED_MIMES)),

            'phone.required' => sprintf(__("validator.required"), __('phone')),
            'phone.unique' => sprintf(__("validator.unique"), __('phone')),
            'phone.digits' => sprintf(__("validator.digits"), __('phone'), 11),
            'phone.numeric' => sprintf(__("validator.numeric"), __('phone')),
            'phone_verify.required' => sprintf(__("validator.required"), __('phone_verify')),
            'phone_verify.exists' => sprintf(__("validator.invalid"), __('phone_verify')),
            'phone.exists' => __('user_phone_not_found'),


            'national_code.required' => sprintf(__("validator.required"), __('national_code')),
            'national_code.digits' => sprintf(__("validator.digits"), __('national_code'), 16),
            'national_code.unique' => sprintf(__("validator.unique"), __('national_code')),
            'national_code.numeric' => sprintf(__("validator.numeric"), __('national_code')),

            'card.required' => sprintf(__("validator.required"), __('card')),
            'card.digits' => sprintf(__("validator.digits"), __('card'), 16),
            'card.unique' => sprintf(__("validator.unique"), __('card')),
            'card.numeric' => sprintf(__("validator.numeric"), __('card')),

            'sheba.required' => sprintf(__("validator.required"), __('sheba')),
            'sheba.digits' => sprintf(__("validator.digits"), __('sheba'), 24),
            'sheba.unique' => sprintf(__("validator.unique"), __('sheba')),
            'sheba.numeric' => sprintf(__("validator.numeric"), __('sheba')),

            'password.required' => sprintf(__("validator.required"), __('password')),
            'password.regex' => sprintf(__("validator.password_regex"),),
            'password.confirmed' => sprintf(__("validator.password_confirmed"),),
            'password.min' => sprintf(__("validator.min_len"), 6, mb_strlen($this->password)),

            'status.required' => sprintf(__("validator.required"), __('status')),
            'status.exists' => sprintf(__("validator.invalid"), __('status')),
            'access_id.required' => sprintf(__("validator.required"), __('role')),
            'access_id.exists' => sprintf(__("validator.invalid"), __('role')),

        ];
    }
}
