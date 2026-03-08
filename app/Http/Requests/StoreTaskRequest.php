<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'titulo' => 'required|string|max:100',
            'nicho' => 'nullable|exists:nichos,id',
            'variacao' => 'required|in:false,true',
            'parent_id' => 'required_if:variacao,true|nullable|exists:tasks,id',
            'variation_number' => 'nullable|integer|min:1',
            'link_doc' => 'nullable|url',
            'fonte_trafego' => 'required|exists:platforms,id',
            'copywriter_id' => 'nullable|exists:users,id',
            'editor_id' => 'nullable|exists:users,id',
            'gestor_id' => 'required|exists:users,id',
            'prazo_copy' => 'nullable|date',
            'prazo_editor' => 'nullable|date',
            'code' => 'required',
            'parentSearch' => 'nullable|string',
            'gestor_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'O título da demanda é obrigatório.',
            'titulo.max' => 'O título deve ter no máximo 255 caracteres.',

            'nicho.required' => 'Selecione um nicho.',
            'nicho.exists' => 'O nicho selecionado é inválido.',

            'variacao.required' => 'Informe se é variação ou não.',
            'variacao.in' => 'Valor inválido para variação.',

            'link_doc.url' => 'O link do documento deve ser uma URL válida.',

            'fonte_trafego.required' => 'Selecione a fonte de tráfego.',
            'fonte_trafego.exists' => 'A fonte de tráfego selecionada é inválida.',

            'copywriter_id.exists' => 'Copywriter inválido.',
            'editor_id.exists' => 'Editor inválido.',

            'prazo_copy.date' => 'O prazo do copywriter deve ser uma data válida.',
            'prazo_editor.date' => 'O prazo do editor deve ser uma data válida.',

            'code.required' => 'O código do criativo é obrigatório.',

            'gestor_id.exists' => 'Gestor inválido.',
            'gestor_id.required' => 'O gestor é obrigatório.',
        ];
    }
}
