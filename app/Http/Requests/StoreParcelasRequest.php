<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'descripcion_parcela' => [
                'required',
                Rule::unique('parcelas')->where(function ($query) {
                    return $query->where('descripcion_parcela', $this->descripcion_parcela)
                                 ->where('id_lote', $this->lote)
                                 ->where('manzana', $this->manzana);
                })->ignore($this->parcela?->id_parcela, 'id_parcela')
            ],
            'superficie_parcela' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'manzana' => 'required',
            'cantidad_bolsas' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'ancho' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'largo' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'lote' => 'required|numeric|exists:lotes,id_lote',
        ];
    }

    public function messages()
    {
        return [
            'descripcion_parcela.required' => 'El campo Descripción Parcela es obligatorio.',
            'descripcion_parcela.unique' => 'Ya existe una Parcela con esa Descripción en el Lote y Manzana ingresados.',
            'superficie_parcela.required' => 'El campo Superficie Parcela es obligatorio.',
            'superficie_parcela.regex' => 'El campo Superficie Parcela debe ser un número con 2 decimales.',
            'manzana.required' => 'El campo Manzana es obligatorio.',
            'cantidad_bolsas.required' => 'El campo Cantidad de Bolsas es obligatorio.',
            'cantidad_bolsas.regex' => 'El campo Cantidad de Bolsas debe ser un número con 2 decimales.',
            'ancho.required' => 'El campo Ancho es obligatorio.',
            'ancho.regex' => 'El campo Ancho debe ser un número con 2 decimales.',
            'largo.required' => 'El campo Largo es obligatorio.',
            'largo.regex' => 'El campo Largo debe ser un número con 2 decimales.',
            'lote.required' => 'El campo Lote es obligatorio.',
            'lote.numeric' => 'El campo Lote debe ser un número.',
            'lote.exists' => 'El campo Lote no existe.',
        ];
    }
}