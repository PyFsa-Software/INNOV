<?php

namespace App\Http\Requests;

use App\Models\Parcela;
use Illuminate\Foundation\Http\FormRequest;

class StoreVentasRequest extends FormRequest
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
            'id_cliente' => 'required|exists:personas,id_persona',
            'id_parcela' => 'required|exists:parcelas,id_parcela',
            'cuotas' => 'required|numeric',
            'precio_total_entrega' => 'required|numeric',
        ];
    }

    public function withValidator($validator)
    {
        // dd($validator->getData());
        $inputs = $validator->getData();

        $parcelaById = Parcela::where([
            ['id_parcela', '=', $inputs['id_parcela']],
        ])->get()[0];

        $precioTotalTerreno = round($parcelaById->cantidad_bolsas * $inputs['promedio_cemento']);

        if ($inputs['precio_total_entrega'] > $precioTotalTerreno) {
            $validator->errors()->add(
                'precio_total_entrega',
                'La entrega no puede ser mayor al precio total de la parcela'
            );
        }

        // $validator->after(
        //     function ($validator) use ($email) {

        //     }
        // );
    }
}