<?php

namespace App\Http\Requests;

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
use stdClass;

class AccessRequest extends FormRequest
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
        $tmp = [];

        if (!$this->cmnd) {

            $tmp = array_merge($tmp, [
                'name' => ['required', 'string', 'max:100', "unique:accesses,name,$this->id"],
                'type_id' => ['required', Rule::in(array_column(Variable::AGENCY_TYPES, 'id'))],
                'accesses' => ['required', 'array'],

            ]);

            $this->merge(['agency_level' => collect(Variable::AGENCY_TYPES)
                    ->firstWhere('id', intval($this->type_id))['level'] ?? null]);

        }

        return $tmp;
    }

    public function messages()
    {

        return [


            'accesses.required' => sprintf(__("validator.required"), __('accesses')),
            'accesses.array' => sprintf(__("validator.invalid"), __('accesses')),

            'name.required' => sprintf(__("validator.required"), __('name')),
            'name.unique' => sprintf(__("validator.unique"), __('name')),
            'name.max' => sprintf(__("validator.max_len"), __('name'), 100, mb_strlen($this->name)),
            'name.string' => sprintf(__("validator.string"), __('name')),


            'type_id.required' => sprintf(__("validator.required"), __('agency_type')),
            'type_id.in' => sprintf(__("validator.invalid"), __('agency_type')),

        ];
    }
}
