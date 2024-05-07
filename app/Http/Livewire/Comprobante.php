<?php

namespace App\Http\Livewire;

use App\Enums\DomicilioAlquiler;
use App\Enums\FormasPago;
use App\Enums\MonedaPago;
use App\Models\Comprobante as ComprobanteModel;
use App\Models\DetalleVenta;
use App\Models\Persona;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Comprobante extends Component
{
    // props
    public $clientes;
    public $ventasCliente;
    public $formasDePagos;
    public $domiciliosAlquiler;

    public $descripcionComprobante = "";
    public $clienteCombo = "";
    public $ventasClienteCombo = "";
    public $formaPago = "";
    public $importeTotal = "";
    public $conceptoDe = "";

    public $srSra = "";
    public $dni = "";
    public $domicilio = "";
    public $domicilioAlquiler = "";

    public $isDisabled = true;

    public function mount()
    {
        $this->clientes = Persona::where([
            ['cliente', '=', '1'],
            ['activo', '=', '1'],
        ])->get();
        $this->formasDePagos = FormasPago::toArray();
        $this->domiciliosAlquiler = DomicilioAlquiler::toArray();
    }


    protected $rules = [
        'descripcionComprobante' => 'required|string|unique:comprobantes,descripcion_comprobante',
        'clienteCombo' => 'nullable|numeric|integer',
        'ventasClienteCombo' => 'nullable|numeric|integer',
        'formaPago' => 'required|string',
        'importeTotal' => 'required|numeric|min:1',
        'conceptoDe' => 'required|string',
    ];

    public function updated()
    {
        $this->isDisabled = true;
        try {
            $this->loadClienteCombo($this->clienteCombo);

            if(!$this->clienteCombo){
                $this->rules['srSra'] = 'required|string';
                $this->rules['dni'] = 'required|regex:/^[0-9]{7,8}$/';
                $this->rules['domicilio'] = 'required|string';
                $this->rules['domicilioAlquiler'] = 'nullable|string';
            }else{
                unset($this->rules['srSra']);
                unset($this->rules['dni']);
                unset($this->rules['domicilio']);
                unset($this->rules['domicilioAlquiler']);
            }
           // Agregar regla de validación condicional para $ventasClienteCombo
            if ($this->clienteCombo) {
                $this->rules['ventasClienteCombo'] = 'required|numeric|integer';
            } else {
                unset($this->rules['ventasClienteCombo']);
            }

            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, no desactivamos el botón
            $this->isDisabled = false;
            throw $e;
        }
        $this->isDisabled = false;
    }

    // if change property $clienteCombo search in model VentasCliente
    public function loadClienteCombo($value)
    {
        if ($value == null) {
            $this->ventasCliente = null;
            return;
        }
        $this->ventasCliente = Venta::where('id_cliente', $value)->with('parcela.lote')->get();
    }

    public function submit()
    {
        try {
            DB::beginTransaction();
            // Crear comprobante
            $comprobante = new ComprobanteModel();
            $comprobante->descripcion_comprobante = $this->descripcionComprobante;
            $comprobante->numero_recibo = DetalleVenta::getSiguienteNumeroRecibo();
            if ($this->clienteCombo){
                $comprobante->id_cliente = $this->clienteCombo;
                $comprobante->id_venta = $this->ventasClienteCombo;
            }else{
                $comprobante->id_cliente = null;
                $comprobante->id_venta = null;
                $comprobante->sr_sra = $this->srSra;
                $comprobante->dni = $this->dni;
                $comprobante->domicilio = $this->domicilio;
                $comprobante->domicilio_alquiler = $this->domicilioAlquiler;
            }
            $comprobante->fecha_comprobante = date('Y-m-d');
            $comprobante->forma_pago = $this->formaPago;
            $comprobante->importe_total = $this->importeTotal;
            $comprobante->concepto_de = $this->conceptoDe;
            $comprobante->save();
            DB::commit();
            return redirect()->route('comprobantes.crear')->with('success', "Comprobante creado exitosamente <a href=" . route('comprobantes.pdf', $comprobante->id_comprobante) . " target='_blank'>Haga click aqui </a>para descargar el comprobante."
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            return redirect()->route('comprobantes.crear')->with('error', "Error al crear el comprobante.");
        }
    }

    public function render()
    {
        return view('livewire.comprobante');
    }
}