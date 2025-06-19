@extends('layouts.app')
@section('title', 'SIS 3.0 | Gestión de permisos')

@section('content')
<div class="container-fluid">

    {{-- 🧭 Migas de pan --}}
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('inicio') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Gestión de permisos</li>
    @endsection

    <h2 class="mb-3 text-titulo">Gestión de permisos</h2>

    {{-- 🎛 Botonera --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ url()->previous() }}"
           class="btn btn-secondary btn-principal">
            <i class="fa fa-arrow-left me-1"></i> Regresar
        </a>

        {{-- Botón Modal catálogo de roles --}}
        <button type="button"
                class="btn btn-primary btn-principal"
                data-bs-toggle="modal"
                data-bs-target="#modalRoles">
            <i class="fa fa-users-cog me-1"></i> Catálogo de roles
        </button>

        {{-- Botón Modal catálogo de permisos --}}
        <button type="button"
                class="btn btn-primary btn-principal"
                data-bs-toggle="modal"
                data-bs-target="#modalPermisos">
            <i class="fa fa-key me-1"></i> Catálogo de permisos
        </button>
    </div>

    {{-- 🔎 Filtro por ejecutivo --}}
    <form method="GET" action="{{ route('permisos.index') }}">
        <div class="card mb-3">
            <div class="card-header text-center">
                <h5 class="mb-0 text-subtitulo">Filtros</h5>
            </div>

            <div class="card-body">
                <div class="row gx-3 gy-2 justify-content-between">
                    {{-- Ejecutivos --}}
                    <div class="col">
                        <label for="ejecutivo" class="form-label text-normal">Ejecutivo</label>
                        <select name="ejecutivo"
                                id="ejecutivo"
                                class="form-select select2"
                                data-placeholder="Todos">
                            <option value="">Todos</option>
                            @foreach ($usuarios as $u)
                                <option value="{{ $u->id_usuario }}"
                                        {{ request('ejecutivo') == $u->id_usuario ? 'selected' : '' }}>
                                    {{ $u->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Acciones --}}
                    <div class="col d-flex align-items-end gap-2">
                        <a href="{{ route('permisos.index') }}"
                           class="btn btn-secondary"
                           style="width: 50%;">
                            <i class="fa fa-eraser me-1"></i> Limpiar
                        </a>

                        <button type="submit"
                                class="btn btn-success"
                                style="width: 50%;">
                            <i class="fa fa-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- 📋 Tabla de usuarios con roles / permisos --}}
    <div class="table-responsive mb-3 shadow-lg">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="text-center align-middle">
                <tr>
                    <th class="header-tabla py-1 px-2">#</th>
                    <th class="header-tabla py-1 px-2">Usuario</th>
                    <th class="header-tabla py-1 px-2">Rol(es)</th>
                    <th class="header-tabla py-1 px-2">Permisos directos</th>
                    <th class="header-tabla py-1 px-2">Acciones</th>
                </tr>
            </thead>
            <tbody id="listaUsuarios">
                @foreach ($usuarios as $u)
                    <tr data-id="{{ $u->id_usuario }}" data-username="{{ $u->username }}">
                        <td class="py-1 px-2 text-center">{{ $u->id_usuario }}</td>
                        <td class="py-1 px-2">{{ $u->username }}</td>

                        {{-- Roles --}}
                        <td class="py-1 px-2">
                            @foreach ($u->roles as $r)
                                <span class="badge bg-info text-dark me-1">{{ $r->name }}</span>
                            @endforeach
                        </td>

                        {{-- Permisos directos (sin rol) --}}
                        <td class="py-1 px-2" data-permisos>
                            @forelse ($u->getDirectPermissions() as $p)
                                <span class="badge bg-secondary me-1">
                                {{ $p->name }}
                                <i class="fa fa-times ms-1 small"
                                    style="cursor:pointer"
                                    data-permiso-remove="{{ $p->name }}"></i>
                                </span>
                            @empty
                                —
                            @endforelse
                        </td>

                        {{-- Botones --}}
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-success"
                                    data-btn-asignar>
                                <i class="fa fa-plus"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-btn-permisos
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPermisosUsuario">
                                <i class="fa fa-cog"></i> Permisos
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div> {{-- /.container-fluid --}}

{{-- 🗂️ Modal catálogo de roles --}}
<div class="modal fade" id="modalRoles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catálogo de roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Tabla sencilla de roles --}}
                <ul class="list-group">
                    @foreach ($roles as $r)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($r->name) }}
                            <span class="badge bg-primary rounded-pill">
                                {{ $r->permissions->count() }} permisos
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- 🗂️ Modal catálogo de permisos --}}
<div class="modal fade" id="modalPermisos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catálogo de permisos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Lista agrupada por módulo --}}
                <ul class="list-group">
                    @foreach ($permisos->groupBy(function($p){ return Str::before($p->name, '.'); }) as $modulo => $items)
                        <li class="list-group-item">
                            <strong class="text-uppercase">{{ $modulo }}</strong>
                            <ul class="mt-2">
                                @foreach ($items as $p)
                                    <li>{{ $p->name }}</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Modal ASIGNAR --}}
<div class="modal fade" id="modalAsignar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formAsignar" class="modal-content">
      @csrf
      <input type="hidden" id="asigna_uid" name="usuario_id">

      <div class="modal-header">
        <h5 class="modal-title">Agregar permiso a <span id="asigna_name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <select id="asigna_select" name="permiso" class="form-select"></select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-success">
          <i class="fa fa-plus me-1"></i> Agregar
        </button>
      </div>
    </form>
  </div>
</div>



{{-- Modal REMOVER (similar) --}}
<div class="modal fade" id="modalRemover" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formRemover" class="modal-content">
      @csrf
      <input type="hidden" name="usuario_id" id="quita_uid">
      <input type="hidden" name="permiso" id="quita_permiso">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Quitar permiso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="quita_texto"></div>
      <div class="modal-footer">
        <button class="btn btn-danger">
          <i class="fa fa-trash me-1"></i> Quitar
        </button>
      </div>
    </form>
  </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

  const csrf   = document.querySelector('meta[name="csrf-token"]').content;
  const modal  = new bootstrap.Modal('#modalAsignar');
  const select = document.getElementById('asigna_select');
  const uidInp = document.getElementById('asigna_uid');
  const nameSp = document.getElementById('asigna_name');

  /* Abre modal y llena opciones */
  document.querySelectorAll('[data-btn-asignar]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const tr   = btn.closest('tr');
      const uid  = tr.dataset.id;
      const user = tr.dataset.username;

      uidInp.value = uid;
      nameSp.textContent = user;

      // permisos directos + heredados
      const res = await fetch(`/api/permisos-usuario/${uid}`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
      const {permisos, heredados} = await res.json();

      // todos los permisos disponibles
      const todos = @json($permisos->pluck('name'));
      const faltantes = todos.filter(p => !permisos.includes(p) && !heredados.includes(p));

      // pinta select
      select.innerHTML = faltantes.length
        ? faltantes.map(p => `<option value="${p}">${p}</option>`).join('')
        : '<option disabled>No hay permisos disponibles</option>';

      modal.show();
    });
  });

  /* Enviar formulario */
  document.getElementById('formAsignar').addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    fd.append('_token', csrf);

    const r = await fetch('{{ route("permisos.asignar") }}', {
      method: 'POST', body: fd
    });
    const {ok, permisos} = await r.json();
    if (ok) {
      actualizarBadges(uidInp.value, permisos);
      modal.hide();
    }
  });

  /* helper: repinta badges */
  function actualizarBadges(uid, perms) {
    const tr = document.querySelector(`tr[data-id="${uid}"]`);
    const td = tr.querySelector('[data-permisos]');
    td.innerHTML = perms.length
      ? perms.map(p => `<span class="badge bg-secondary me-1">${p}</span>`).join('')
      : '—';
  }
});
</script>
@endpush



@endsection
