<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarFacturaRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            // 'cliente_id' => 'required|exists:clientes,id',
            // 'lineas_factura' => 'required|array',
            // 'lineas_factura.*.cantidad' => 'required|numeric',
            // 'lineas_factura.*.articulo_id' => 'required|exists:articulos,id',
            // 'lineas_factura.*.sub_total' => 'required|numeric',
            // 'divisas_factura' => 'required|array',
            // 'divisas_factura.*.divisa_id' => 'required|exists:divisas,id',
            // 'divisas_factura.*.monto' => 'required|numeric',
            // 'divisas_factura.*.tasa' => 'required|numeric',
        ];
    }

    public function all($keys = null)
    {
        if (empty($keys)) {
            return parent::json()->all();
        }

        return collect(parent::json()->all())->only($keys)->toArray();
    }
}
