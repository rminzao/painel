@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Editor de Fúguras</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Nova Fúgura</button>
    </div>

    @php
    $success = session('success');
    @endphp

    @if(is_string($success))
        <div class="alert alert-success">{{ $success }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Sexo</th>
                <th>Tipo</th>
                <th>Atributos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fuguras as $fugura)
            <tr>
                <td>{{ $fugura->ID }}</td>
                <td>{{ $fugura->Name }}</td>
                <td>
                    @if($fugura->Sex === 0) Unissex
                    @elseif($fugura->Sex === 1) Masculino
                    @else Feminino
                    @endif
                </td>
                <td>{{ $fugura->Type }}</td>
                <td>
                    Atk: {{ $fugura->Attack }}, Def: {{ $fugura->Defend }}, Agi: {{ $fugura->Agility }},
                    Luck: {{ $fugura->Luck }}, Dmg: {{ $fugura->Damage }}, Guard: {{ $fugura->Guard }}
                </td>
                <td>
                    <button class="btn btn-sm btn-warning btn-editar"
                        data-id="{{ $fugura->ID }}"
                        data-name="{{ $fugura->Name }}"
                        data-sex="{{ $fugura->Sex }}"
                        data-type="{{ $fugura->Type }}"
                        data-attack="{{ $fugura->Attack }}"
                        data-defend="{{ $fugura->Defend }}"
                        data-agility="{{ $fugura->Agility }}"
                        data-luck="{{ $fugura->Luck }}"
                        data-blood="{{ $fugura->Blood }}"
                        data-damage="{{ $fugura->Damage }}"
                        data-guard="{{ $fugura->Guard }}"
                        data-cost="{{ $fugura->Cost }}"
                        data-bs-toggle="modal" data-bs-target="#editModal">
                        Editar
                    </button>

                    <form action="{{ url('admin/gameutils/fugura/delete/' . $fugura->ID) }}" method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="{{ $_SESSION['token'] ?? '' }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">Deletar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de criação -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ url('admin/gameutils/fugura/store') }}" method="POST">
        <input type="hidden" name="_token" value="{{ $_SESSION['token'] ?? '' }}">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Nova Fúgura</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="Name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Sexo</label>
                <select name="Sex" class="form-control">
                    <option value="0">Unissex</option>
                    <option value="1">Masculino</option>
                    <option value="2">Feminino</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Tipo</label>
                <input type="number" name="Type" class="form-control" required>
            </div>
            @foreach(['Attack','Defend','Agility','Luck','Blood','Damage','Guard','Cost'] as $attr)
                <div class="mb-2">
                    <label>{{ $attr }}</label>
                    <input type="number" name="{{ $attr }}" class="form-control">
                </div>
            @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de edição -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST" action="">
         <input type="hidden" name="_token" value="{{ $_SESSION['token'] ?? '' }}">
        <input type="hidden" name="_method" value="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar Fúgura</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="Name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Sexo</label>
                <select name="Sex" class="form-control">
                    <option value="0">Unissex</option>
                    <option value="1">Masculino</option>
                    <option value="2">Feminino</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Tipo</label>
                <input type="number" name="Type" class="form-control" required>
            </div>
            @foreach(['Attack','Defend','Agility','Luck','Blood','Damage','Guard','Cost'] as $attr)
                <div class="mb-2">
                    <label>{{ $attr }}</label>
                    <input type="number" name="{{ $attr }}" class="form-control">
                </div>
            @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Atualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  console.log("JS inline carregado!");
</script>
<script src="{{ url('assets/js/admin/fugura/fugura.js') }}"></script>
@endpush
