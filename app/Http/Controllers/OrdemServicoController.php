<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrdemServico;

class OrdemServicoController extends Controller
{
    public function index()
    {
        $ordens = OrdemServico::all();
        return view('welcome', compact('ordens'));
    }

    public function store(Request $request)
    {
        OrdemServico::create($request->all());
        return redirect()->route('ordem_servico.index');
    }

    public function updateTecnico(Request $request, $id)
    {
        $ordem = OrdemServico::find($id);
        $ordem->tecnico_nome = $request->novo_tecnico;
        $ordem->save();

        return redirect()->route('ordem_servico.index');
    }

    public function updateStatus(Request $request, $id)
    {
        $ordem = OrdemServico::find($id);
        $ordem->status = $request->novo_status;
        $ordem->save();

        return redirect()->route('ordem_servico.index');
    }

    public function destroy($id)
    {
        $ordem = OrdemServico::find($id);
        $ordem->delete();

        return redirect()->route('ordem_servico.index');
    }
}
