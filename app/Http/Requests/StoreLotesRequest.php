<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLotesRequest extends FormRequest
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
        return [
            'nombre_lote' => 'required|unique:lotes,nombre_lote,' . $this->lote?->id_lote . ',id_lote',
            'hectareas_lote' => 'required|numeric|min:1',
            'cantidad_manzanas' => 'required|numeric|min:1',
            'ubicacion' => 'required|string|min:1',
        ];
    }
}