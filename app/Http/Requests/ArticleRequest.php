<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch (request()->route()->getActionMethod()) {
            case 'store':
                $rules = [
                    'title' => 'required|string',
                    'content' => 'required|string',
                    'enabled' => 'required|boolean',
                    "image" => 'image|nullable'
                ];
                break;
            case 'update':
                $rules = [
                    'title' => 'required|string',
                    'content' => 'required|string',
                    'enabled' => 'required|boolean',
                    "image" => 'image|nullable'
                ];
                break;
            case 'showMore':
                $rules = [
                    'search_word' => 'nullable|string',
                    'sort' => 'required_with:order|in:title,created_at,updated_at',
                    'order' => 'required_with:sort|in:desc,asc',
                    'page' => 'required_with:per_page|numeric',
                    'per_page' => 'required_with:page|numeric',
                ];
                break;
            default:
                return [];
        }

        return $rules;
    }
}
