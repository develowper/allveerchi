<?php

namespace App\Http\Requests;

use App\Models\Agency;
use App\Models\Brand;
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

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
        $this->tags = is_array($this->tags) ? join(',', $this->tags) : $this->tags;
        $tmp = [];
        $categories = Category::pluck('id');

        if (!$this->cmnd) {

            $tmp = array_merge($tmp, [
                'name' => ['required', 'max:200', Rule::unique('products', 'name')->ignore($this->id)],
                'name_en' => ['required', 'max:200', Rule::unique('products', 'name_en')->ignore($this->id)],
                'PN' => ['nullable', 'string', 'max:20'],
                'tags' => ['nullable', 'string', 'max:1024'],
//                'category_id' => ['nullable', Rule::in($categories)],

                'categories' => ['nullable', 'array'],
                'categories.*' => ['nullable', Rule::in($categories)],
//                "weight" => ['required', 'numeric', 'gte:0', 'lt:99999', /*$this->pack_id == null ? Rule::in(1) :*/ 'numeric'],

//                "price" => ['required', 'numeric', 'gte:0'],
//                "in_repo" => ['required', 'numeric', 'gte:0', 'lt:99999'],
//                "in_shop" => ['required', 'numeric', 'gte:0', 'lt:99999'],
//
            ]);
        }
        if ($this->uploading)
            $tmp = array_merge($tmp, [
                'img' => ['sometimes', 'base64_image_size:' . Variable::PRODUCT_IMAGE_LIMIT_MB * 1024, 'base64_image_mime:' . implode(",", Variable::PRODUCT_ALLOWED_MIMES)],

            ]);
        if ($this->cmnd)
            $tmp = array_merge($tmp, [
            ]);
        return $tmp;
    }

    public function messages()
    {

        return [

            "weight.required" => sprintf(__("validator.required"), __('weight')),
            "weight.numeric" => sprintf(__("validator.numeric"), __('weight')),
            "weight.gte" => sprintf(__("validator.gt"), __('weight'), 0),

            'name.required' => sprintf(__("validator.required"), __('name')),
            'name.unique' => sprintf(__("validator.unique"), __('name')),
            'name.max' => sprintf(__("validator.max_len"), __('name'), 200, mb_strlen($this->name)),

            'name_en.required' => sprintf(__("validator.required"), __('name_en')),
            'name_en.unique' => sprintf(__("validator.unique"), __('name_en')),
            'name_en.max' => sprintf(__("validator.max_len"), __('name_en'), 200, mb_strlen($this->name_en)),

            'tags.max' => sprintf(__("validator.max_len"), __('tags'), 1024, mb_strlen($this->tags)),

            'img.required' => sprintf(__("validator.required"), __('image')),
            'img.base64_image_size' => sprintf(__("validator.max_size"), __("image"), Variable::PRODUCT_IMAGE_LIMIT_MB),
            'img.base64_image_mime' => sprintf(__("validator.invalid_format"), __("image"), implode(",", Variable::PRODUCT_ALLOWED_MIMES)),

            'category_id.required' => sprintf(__("validator.required"), __('category')),
            'category_id.in' => sprintf(__("validator.invalid"), __('category')),

            "price.required" => sprintf(__("validator.required"), __('price')),
            "price.numeric" => sprintf(__("validator.numeric"), __('price')),
            "price.gte" => sprintf(__("validator.gt"), __('price'), 0),

            "in_repo.required" => sprintf(__("validator.required"), __('repository_count')),
            "in_repo.numeric" => sprintf(__("validator.numeric"), __('repository_count')),
            "in_repo.gte" => sprintf(__("validator.gt"), __('repository_count'), 0),

            "in_shop.required" => sprintf(__("validator.required"), __('shop_count')),
            "in_shop.numeric" => sprintf(__("validator.numeric"), __('shop_count')),
            "in_shop.gte" => sprintf(__("validator.gt"), __('shop_count'), 0),

            'categories.array' => sprintf(__("validator.invalid"), __('categories')),
            'categories.*.in' => sprintf(__("validator.invalid"), __('categories')),

            'PN.max' => sprintf(__("validator.max_len"), __('PN'), 20, mb_strlen($this->PN)),

        ];
    }
}
