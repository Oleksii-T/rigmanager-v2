<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Validation\Rule;

class SubscriptionPlanRequest extends FormRequest
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
        $model = $this->route('subscription_plan');

        $rules = [
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'array'],
            'slug.en' => ['required', 'string', 'max:255'],
            'description' => ['required', 'array'],
            'description.en' => ['required', 'string', 'max:5000'],
            'trial' => ['required', 'integer', 'min:0', 'max:999'],
        ];

        if ($model) {
            return $rules;
        }

        $rules += [
            'price' => ['required', 'numeric', 'min:0.01', 'max:9999'],
            'interval' => ['required', 'string', Rule::in(SubscriptionPlan::INTERVALS)],
        ];

        return $rules;
    }
}
