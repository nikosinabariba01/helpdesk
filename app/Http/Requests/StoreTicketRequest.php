<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject'         => ['required', 'string', 'max:255'],
            'Jenis_Pengaduan' => ['required'],
            'Lokasi'          => ['required', 'string', 'max:255'],
            'Detail'       => ['required', 'string'],
            'gambar'          => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }
}
