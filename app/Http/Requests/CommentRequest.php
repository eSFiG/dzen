<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_name' => 'required|max:255',
            'email' => 'required|max:255',
            'parent_id' => 'max:255',
            'text' => 'required',
            'captcha' => 'required|captcha_api:' . request('key') . ',math'
        ];
    }

}
