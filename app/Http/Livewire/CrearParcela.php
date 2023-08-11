<?php

namespace App\Http\Livewire;

use App\Models\Parcela;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CrearParcela extends Component
{

    public $lotes;
    public $idLote = "0";
    public $cantidadParcelas;
    public $manzana;
    public $ancho;
    public $largo;
    public $inputs = [];
    public $isDisabled = true;


    public function attributes()
    {
        $attributes = [];

        foreach ($this->inputs as $key => $value) {
            $attributes["inputs.{$key}.descripcion_parcela"] = "Descripción de Parcela en la parcela " . ($key + 1);
            $attributes["inputs.{$key}.superficie_parcela"] = "Superficie de Parcela en la parcela " . ($key + 1);
            $attributes["inputs.{$key}.cantidad_bolsas"] = "Cantidad de Bolsas en la parcela " . ($key + 1);
            $attributes["inputs.{$key}.ancho"] = "Ancho en la parcela " . ($key + 1);
            $attributes["inputs.{$key}.largo"] = "Largo en la parcela " . ($key + 1);
        }

        return $attributes;
    }

    protected $messages = [
        'idLote.required' => 'El campo Lote es obligatorio.',
        'idLote.not_in' => 'El campo Lote seleccionado no es válido.',
        'idLote.exists' => 'El campo Lote seleccionado no existe.',
        'manzana.required' => 'El campo Manzana es obligatorio.',
        'manzana.string' => 'El campo Manzana debe ser una cadena de caracteres.',
        'manzana.regex' => 'El campo Manzana debe ser una letra mayúscula.',
        'manzana.unique' => 'El campo Manzana ya existe en el lote seleccionado.',
        'inputs.*.descripcion_parcela.required' => 'El campo Descripción de Parcela es obligatorio.',
        'inputs.*.descripcion_parcela.string' => 'El campo Descripción de Parcela debe ser una cadena de caracteres.',
        'inputs.*.descripcion_parcela.unique' => 'El campo Descripción de Parcela debe ser único.',
        'inputs.*.superficie_parcela.required' => 'El campo Superficie de Parcela es obligatorio',
        'inputs.*.superficie_parcela.numeric' => 'El campo Superficie de Parcela debe ser un número.',
        'inputs.*.superficie_parcela.min' => 'El campo Superficie de Parcela debe ser mayor a 0.',
        'inputs.*.cantidad_bolsas.required' => 'El campo Cantidad de Bolsas es obligatorio.',
        'inputs.*.cantidad_bolsas.numeric' => 'El campo Cantidad de Bolsas debe ser un número.',
        'inputs.*.cantidad_bolsas.min' => 'El campo Cantidad de Bolsas debe ser mayor a 0.',
        'inputs.*.ancho.required' => 'El campo Ancho es obligatorio.',
        'inputs.*.ancho.numeric' => 'El campo Ancho debe ser un número.',
        'inputs.*.ancho.min' => 'El campo Ancho debe ser mayor a 0.',
        'inputs.*.largo.required' => 'El campo Largo es obligatorio.',
        'inputs.*.largo.numeric' => 'El campo Largo debe ser un número.',
        'inputs.*.largo.min' => 'El campo Largo debe ser mayor a 0.',
    ];

    protected function rules()
    {
        return [
            'idLote' => 'required|not_in:0|exists:lotes,id_lote',
            'manzana' => [
                'required',
                'string',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('parcelas')->where(function ($query) {
                    return $query->where('id_lote', $this->idLote);
                }),
            ],
            'inputs.*.descripcion_parcela' => [
                'required',
                'string',
                'unique:parcelas,descripcion_parcela',
            ],
            'ancho' => 'required|numeric|min:1',
            'largo' => 'required|numeric|min:1',
            'inputs.*.superficie_parcela' => 'required|numeric|min:0',
            'inputs.*.cantidad_bolsas' => 'required|numeric|min:0',
            'inputs.*.ancho' => 'required|numeric|min:0',
            'inputs.*.largo' => 'required|numeric|min:0',
        ];
    }



    public function updated($propertyName)
    {
        try {
            $this->validateOnly($propertyName);
            $this->isDisabled = false;
        } catch (\Throwable $e) {
            $this->isDisabled = true;
            // dd($e);
            $errors = $e->validator->errors();
            foreach ($errors->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
            return;
        }
    }

    public function generarInputs()
    {
        $inputs = [];

        for ($i = 1; $i <= $this->cantidadParcelas; $i++) {
            $inputs[] = [
                'numero_parcela' => $this->inputs[$i - 1]['numero_parcela'] ?? $i,
                'descripcion_parcela' => $this->inputs[$i - 1]['descripcion_parcela'] ?? ' Parcela ' . $i . '-Manzana ' . $this->manzana,
                'superficie_parcela' => $this->inputs[$i - 1]['superficie_parcela'] ?? intval($this->ancho) * intval($this->largo),
                'manzana' => $this->manzana,
                'cantidad_bolsas' => $this->inputs[$i - 1]['cantidad_bolsas'] ?? '',
                'ancho' => $this->inputs[$i - 1]['ancho'] ?? intval($this->ancho),
                'largo' => $this->inputs[$i - 1]['largo'] ?? intval($this->largo),
            ];
        }
        $this->inputs = $inputs;
    }

    public function updatedManzana()
    {
        $this->generarInputs();
        for($i = 0; $i < count($this->inputs); $i++){
            $this->inputs[$i]['descripcion_parcela'] = ' Parcela ' . $this->inputs[$i]['numero_parcela'] . '-Manzana ' . $this->manzana;
        }
    }

    public function updatedCantidadParcelas()
    {
        $this->generarInputs();
    }
    public function updatedInputs()
    {
        $this->validateInputs();
    }

    public function updatedAncho()
    {
        foreach ($this->inputs as $key => $input) {
            $this->inputs[$key]['ancho'] = intval($this->ancho);
            $this->inputs[$key]['superficie_parcela'] = intval($this->ancho) * intval($this->largo);
        }
    }

    public function updatedLargo()
    {
        foreach ($this->inputs as $key => $input) {
            $this->inputs[$key]['largo'] = intval($this->largo);
            $this->inputs[$key]['superficie_parcela'] = intval($this->ancho) * intval($this->largo);
        }
    }


    public function validateInputs()
    {
        foreach ($this->inputs as $key => $input) {
            $duplicados = collect($this->inputs)->filter(function ($item) use ($input) {
                return $item['descripcion_parcela'] === $input['descripcion_parcela'];
            });

            if ($duplicados->count() > 1) {
                $mensajeError = "El campo con el N° de parcela " . $input['numero_parcela'] . ", posee una descripción igual a las siguientes parcelas:<br>";

                $duplicados->each(function ($item) use (&$mensajeError, $input) {
                    if ($item['numero_parcela'] === $input['numero_parcela']) {
                        return;
                    }
                    $mensajeError .= "* Parcela N°: " . $item['numero_parcela'] . "<br>";
                });

                $this->addError("inputs.$key.descripcion_parcela", $mensajeError);
            }
        }
    }

    public function submit(){
        $this->validate();

        try {
            DB::beginTransaction();
            foreach ($this->inputs as $key => $input) {
                Parcela::create([
                    'descripcion_parcela' => $input['descripcion_parcela'],
                    'superficie_parcela' => $input['superficie_parcela'],
                    'manzana' => $input['manzana'],
                    'cantidad_bolsas' => $input['cantidad_bolsas'],
                    'ancho' => $input['ancho'],
                    'largo' => $input['largo'],
                    'id_lote' => $this->idLote,
                ]);
            }
            DB::commit();

            return redirect()->route('parcelas.index')->with('success', 'Parcelas creadas correctamente!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('parcelas.index')->with('error', 'Error al crear las Parcelas!');
        }

    }

    public function render()
    {
        return view('livewire.crear-parcela');
    }
}
