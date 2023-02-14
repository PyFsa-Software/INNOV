<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelasRequest extends FormRequest
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
        // var_dump($this->parcela);
        return [
            'descripcion_parcela' => 'required|unique:parcelas,descripcion_parcela,' . $this->parcela?->id_parcela . ',id_parcela',
            'superficie_parcela' => 'required|numeric',
            'manzana' => 'required|numeric',
            'cantidad_bolsas' => 'required|numeric',
            'ancho' => 'required|numeric',
            'largo' => 'required|numeric',
            'lote' => 'required|numeric|exists:lotes,id_lote',
        ];
    }
}