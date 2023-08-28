@extends('layouts.main')

@section('title', 'Ordens de Serviço')

@section('content')

<div class="container mt-5">
    <h1>Ordens de Serviço</h1>
    <table class="table mt-3">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Aberto há:</th>
                <th>Dias restantes:</th>
                <th>Técnico:</th>
                <th>Status:</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordens as $ordem)
            <tr>
                <td>
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#modalVisualizar{{ $ordem->id }}">
                        <ion-icon name="eye" class="azul" size="small"></ion-icon>
                    </button>
                </td>
                <td>{{ $ordem->id }}</td>
                <td>
                    @php
                    $abertura = new DateTime($ordem->data_solicitacao);
                    $agora = new DateTime();
                    $intervalo = $agora->diff($abertura);
                    $diasContados = $intervalo->days;

                    echo $diasContados . ' dias</span>';
                    @endphp
                </td>
                <td class="tempo-abertura">
                    @php
                    $prazo = new DateTime($ordem->prazo_atendimento);

                    if ($prazo < $agora) {
                        $tempo=$prazo->diff($agora);
                        $diasAtrasados = $tempo->days;

                        if ($diasAtrasados == 0) {
                            echo '<span class="laranja">' . ($diasAtrasados) . ' dias</span>';
                        } else {
                            echo '<span class="vermelho">' . ($diasAtrasados) . ' dias atrasado</span>';
                        }
                    } else {
                        $tempo = $agora->diff($prazo);
                        $diasRestantes = $tempo->days;

                        if ($diasRestantes >= 5) {
                            $classe = 'azul';
                        } elseif ($diasRestantes == 4 || $diasRestantes == 3) {
                            $classe = 'verde';
                        } elseif ($diasRestantes == 2 || $diasRestantes == 1) {
                            $classe = 'amarelo';
                        } else {
                            $classe = 'laranja';
                        }

                        echo '<span class="' . $classe . '">' . ($diasRestantes + 1) . ' dias</span>';
                    }
                    @endphp
                </td>
                <td>{{ $ordem->tecnico_nome }}</td>
                <td>{{ $ordem->status }}</td>
                <td>
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#modalAlterarTecnico{{ $ordem->id }}">
                        <ion-icon name="body" size="small"></ion-icon>
                    </button>
                </td>
                <td>
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#modalAlterarStatus{{ $ordem->id }}">
                        <ion-icon name="settings" size="small"></ion-icon>
                    </button>
                </td>
                <td>
                    <form action="{{ route('ordem_servico.destroy', ['id' => $ordem->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta ordem de serviço?')">
                            <ion-icon name="close" size="small"></ion-icon>
                        </button>
                    </form>
                </td>
            </tr>
            <!-- Modal altera técnico -->
            <div class="modal fade" id="modalAlterarTecnico{{ $ordem->id }}" tabindex="-1" aria-labelledby="modalAlterarTecnicoLabel{{ $ordem->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAlterarTecnicoLabel{{ $ordem->id }}">Alterar Técnico</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ordem_servico.alterar_tecnico', ['id' => $ordem->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="novoTecnico" class="form-label">Novo Técnico</label>
                                    <input type="text" name="novo_tecnico" class="form-control" id="novoTecnico" placeholder="{{ $ordem->tecnico_nome }}" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal altera status -->
            <div class="modal fade" id="modalAlterarStatus{{ $ordem->id }}" tabindex="-1" aria-labelledby="modalAlterarStatusLabel{{ $ordem->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAlterarStatusLabel{{ $ordem->id }}">Alterar Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ordem_servico.alterar_status', ['id' => $ordem->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="novoStatus" class="form-label">Novo Status</label>
                                    <select class="form-control" name="novo_status" id="novoStatus" required>
                                        <option value="Aberta">Aberta</option>
                                        <option value="Em andamento">Em andamento</option>
                                        <option value="Concluída">Concluída</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal visualiza OS -->
            <div class="modal fade" id="modalVisualizar{{ $ordem->id }}" tabindex="-1" aria-labelledby="modalVisualizarLabel{{ $ordem->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalVisualizarLabel{{ $ordem->id }}">Ordem de Serviço #{{ $ordem->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Status da OS:</strong> {{ $ordem->status }}</p>
                            <p><strong>Data de Solicitação:</strong> {{ date('d/m/Y', strtotime($ordem->data_solicitacao)) }}</p>
                            <p><strong>Prazo de Atendimento:</strong> {{ date('d/m/Y', strtotime($ordem->prazo_atendimento)) }}</p>
                            <p><strong>Técnico Responsável:</strong> {{ $ordem->tecnico_nome }}</p>
                            <p><strong>Endereço de Atendimento:</strong> {{ $ordem->endereco_atendimento }}</p>
                            <p><strong>Motivo do Atendimento:</strong> {{ $ordem->observacao_atendimento }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalCadastro">
        Nova Ordem de Serviço
    </button>

    <!-- Modal cadastro -->
    <div class="modal fade" id="modalCadastro" tabindex="-1" aria-labelledby="modalCadastroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastroLabel">Cadastro de Ordem de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ordem_servico.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tecnicoNome" class="form-label">Nome do Técnico</label>
                            <input type="text" name="tecnico_nome" class="form-control" id="tecnicoNome" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataSolicitacao" class="form-label">Data da Solicitação</label>
                            <input type="date" name="data_solicitacao" class="form-control" id="dataSolicitacao" required>
                        </div>
                        <div class="mb-3">
                            <label for="prazoAtendimento" class="form-label">Prazo de Atendimento</label>
                            <input type="date" name="prazo_atendimento" class="form-control" id="prazoAtendimento" required>
                        </div>
                        <div class="mb-3">
                            <label for="enderecoAtendimento" class="form-label">Endereço do Atendimento</label>
                            <input type="text" name="endereco_atendimento" class="form-control" id="enderecoAtendimento" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="Aberta">Aberta</option>
                                <!-- <option value="Em andamento">Em andamento</option> -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacaoAtendimento" class="form-label">Motivo do Atendimento</label>
                            <input type="text" name="observacao_atendimento" class="form-control" id="observacaoAtendimento" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection