<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientesRequest extends FormRequest
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
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'dni' => 'required|numeric|digits:8|unique:personas,dni,' . $this->persona?->id_persona . ',id_persona',
            'domicilio' => 'required|max:150|unique:personas,domicilio,' . $this->persona?->id_persona . ',id_persona',
            'celular' => 'required|numeric|digits:10|unique:personas,celular,' . $this->persona?->id_persona . ',id_persona',
            'correo' => 'required|email|unique:personas,correo,' . $this->persona?->id_persona . ',id_persona',
        ];
    }
}