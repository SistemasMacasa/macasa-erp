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
                    <tr data-id="{{ $u->id_usuario }}" data-username="{{ $u->username }}" data-nombre="{{ $u->NombreCompleto }}">
                        {{-- ID de usuario --}}
                        <td class="py-1 px-2 text-center">{{ $u->id_usuario }}</td>
                        <td class="py-1 px-2">{{ $u->username }}</td>

                        {{-- Roles --}}
                        <td data-roles>
                            @forelse($u->roles as $r)
                                <span class="badge bg-warning text-dark me-1">
                                {{ $r->name }}
                                <i class="fa fa-times ms-1 small"
                                    style="cursor:pointer"
                                    data-rol-remove="{{ $r->name }}"></i>
                                </span>
                            @empty
                                —
                            @endforelse
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
                            <!-- Asignar roles -->
                            <button type="button"
                                    class="btn btn-sm btn-outline-warning"
                                    data-btn-roles>
                                <i class="fa fa-plus"></i> Roles
                            </button>
                            {{-- Botón para asignar permisos --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-success"
                                    data-btn-asignar>
                                <i class="fa fa-plus"></i> Permisos
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-btn-permisos>
                                <i class="fa fa-cog me-1"></i> Permisos
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div> {{-- /.container-fluid --}}

{{-- 🗂️ Modal Catálogo de Roles y Gestión --}}
<div class="modal fade" id="modalRoles" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Catálogo de roles</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        {{-- 1) Toolbar: Nuevo rol --}}
        <div class="mb-3 d-flex">
          <input type="text" id="nuevoRoleName" class="form-control me-2" placeholder="Nombre del rol">
          <button id="btnCrearRole" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Crear rol
          </button>
        </div>

        <div class="row">
          {{-- 2A) Tabla de roles --}}
          <div class="col-md-6">
            <table class="table table-hover" id="tablaRoles">
              <thead>
                <tr>
                  <th>Rol</th>
                  <th>Usuarios</th>
                  <th>Permisos</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          {{-- 2B) Edición de permisos del rol seleccionado --}}
          <div class="col-md-6">
            <h6>Permisos para <span id="rolSelectedName">–</span></h6>
            <ul id="listaPermisosRol" class="list-group mb-3"
                style="max-height:300px; overflow:auto;"></ul>
            <button id="btnSyncPermisos" class="btn btn-success">
              <i class="fa fa-save me-1"></i> Guardar cambios
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>


{{-- Botón disparador --}}
<button id="btnCatalogoPermisos"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalCatalogoPermisos">
  <i class="fa fa-key me-1"></i> Catálogo de permisos
</button>

{{-- Modal Catálogo de Permisos --}}
<div class="modal fade" id="modalCatalogoPermisos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Catálogo de permisos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        {{-- 1) Crear nuevo permiso --}}
        <div class="mb-3 d-flex">
          <input type="text" id="nuevoPermName" class="form-control me-2" placeholder="Nombre del permiso">
          <button id="btnCrearPermiso" class="btn btn-success">
            <i class="fa fa-plus me-1"></i> Crear permiso
          </button>
        </div>

        <div class="row">
          {{-- 2A) Tabla de permisos --}}
          <div class="col-md-6">
            <table class="table table-hover" id="tablaPermisosCatalogo">
              <thead>
                <tr>
                  <th>Permiso</th>
                  <th>Roles</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          {{-- 2B) (Opcional) roles que usan el permiso --}}
          <div class="col-md-6">
            <h6>Roles con permiso: <span id="permCatSelectedName">–</span></h6>
            <ul id="listaRolesConPermiso" class="list-group mb-3"
                style="max-height:300px; overflow:auto;"></ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>


{{-- Modal ASIGNAR / Quitar Permisos Directos --}}
<div class="modal fade" id="modalAsignar" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Permisos directos de <span id="asigna_name"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- 1) Lista de permisos directos con botón ❌ --}}
        <ul id="listaPermisosUsuario" class="list-group mb-3">
          {{-- se puebla por JS --}}
        </ul>

        <hr>

        {{-- 2) Formulario para asignar nuevo permiso --}}
        <h6>Agregar nuevo permiso</h6>
        <form id="formAsignar" class="d-flex gap-2">
          @csrf
          <input type="hidden" id="asigna_uid" name="usuario_id">

          <select id="asigna_select" name="permiso" class="form-select">
            {{-- opciones por JS --}}
          </select>

          <button class="btn btn-success">
            <i class="fa fa-plus me-1"></i> Agregar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


{{-- Modal REMOVER PERMISO DIRECTO --}}
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

<!-- Modal Roles Usuario -->
<div class="modal fade" id="modalRolesUsuario" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Roles de <span id="roles_user_name"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        {{-- Lista dinámica de roles con ícono X para quitar --}}
        <ul id="listaRolesUsuario" class="list-group mb-3">
        </ul>
        <hr>

        <h6>Asignar nuevo rol</h6>
        <form id="formAsignarRol">
          @csrf
          <input type="hidden" name="usuario_id" id="asigna_rol_uid">
          <div class="input-group">
            <select name="rol" id="asigna_rol_select" class="form-select">
              {{-- opciones llenadas por JS --}}
            </select>
            <button class="btn btn-warning">
              <i class="fa fa-user-plus me-1"></i> Agregar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- 🗂️ Modal Quitar Rol --}}
<div class="modal fade" id="modalRemoverRol" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formRemoverRol" class="modal-content">
      @csrf
      <input type="hidden" name="usuario_id" id="quitaRol_uid">
      <input type="hidden" name="rol" id="quitaRol_nombre">

      <div class="modal-header">
        <h5 class="modal-title text-danger">Quitar rol</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="quitaRol_texto">
        {{-- Se rellenará con JS --}}
      </div>

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
        // ─── Constantes y referencias ─────────────────────────
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Permisos → Asignar
        const permisoAssignModal   = new bootstrap.Modal('#modalAsignar');
        const permisoSelect        = document.getElementById('asigna_select');
        const permisoUidInput      = document.getElementById('asigna_uid');
        const permisoUserSpan      = document.getElementById('asigna_name');
        const permisoAssignForm    = document.getElementById('formAsignar');
        const listaPermisosUsuario = document.getElementById('listaPermisosUsuario');

        // Permisos → Quitar
        const permisoRemoveModal   = new bootstrap.Modal('#modalRemover');
        const permisoRemoveForm    = document.getElementById('formRemover');
        const permisoRemoveUid      = document.getElementById('quita_uid');
        const permisoRemovePerm     = document.getElementById('quita_permiso');
        const permisoRemoveText     = document.getElementById('quita_texto');

        // Roles → Asignar y listar
        const rolAssignModal        = new bootstrap.Modal('#modalRolesUsuario');
        const rolAssignForm         = document.getElementById('formAsignarRol');
        const rolAssignUid          = document.getElementById('asigna_rol_uid');
        const rolAssignUserSpan     = document.getElementById('roles_user_name');
        const rolAssignSelect       = document.getElementById('asigna_rol_select');
        const rolListInModal        = document.getElementById('listaRolesUsuario');

        // Roles → Quitar
        const rolRemoveModal        = new bootstrap.Modal('#modalRemoverRol');
        const rolRemoveForm         = document.getElementById('formRemoverRol');
        const rolRemoveUid          = document.getElementById('quitaRol_uid');
        const rolRemoveName         = document.getElementById('quitaRol_nombre');
        const rolRemoveText         = document.getElementById('quitaRol_texto');

        // Tabla de usuarios
        const usuariosTbody         = document.getElementById('listaUsuarios');

        // ─── 1) ABRIR modal Asignar Permiso ────────────────────
        document.querySelectorAll('[data-btn-asignar]').forEach(btn => 
        {
            btn.addEventListener('click', async () => 
            {
                const tr   = btn.closest('tr');
                const uid  = tr.dataset.id;
                const user = tr.dataset.username;

                // datos básicos
                permisoUidInput.value      = uid;
                permisoUserSpan.textContent= user;

                // fetch de permisos
                const res = await fetch(`/api/permisos-usuario/${uid}`);
                const { permisos, heredados } = await res.json();

                // lista de permisos directos (solo permisos)
                listaPermisosUsuario.innerHTML = permisos.length
                ? permisos.map(p => `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${p}
                        <i class="fa fa-times text-danger"
                        style="cursor:pointer"
                        data-perm-remove="${p}"></i>
                    </li>`).join('')
                : `<li class="list-group-item text-muted">Sin permisos directos</li>`;

                // select de permisos faltantes
                const allPerms = @json($permisos->pluck('name'));
                const faltantes = allPerms.filter(
                p => !permisos.includes(p) && !heredados.includes(p)
                );
                permisoSelect.innerHTML = faltantes.length
                ? faltantes.map(p => `<option value="${p}">${p}</option>`).join('')
                : `<option disabled>No hay permisos disponibles</option>`;

                permisoAssignModal.show();
            });
        });

        // ─── 2) SUBMIT Asignar Permiso ────────────────────────
        permisoAssignForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(permisoAssignForm);
            fd.append('_token', csrfToken);

            const res = await fetch('{{ route("permisos.asignar") }}', { method:'POST', body:fd });
            const { ok, permisos } = await res.json();
            if (ok) {
            actualizarPermisosBadges(permisoUidInput.value, permisos);
            permisoAssignModal.hide();
            }
        });

        //  dentro de DOMContentLoaded
        listaPermisosUsuario.addEventListener('click', async e => 
        {
            const btn = e.target.closest('[data-perm-remove]');
            if (!btn) return;

            // datos
            const perm = btn.dataset.permRemove;
            const uid  = permisoUidInput.value;

            // confirmar inmediantamente, o podrías abrir otro modal si prefieres
            const fd = new FormData();
            fd.append('_token', csrfToken);
            fd.append('usuario_id', uid);
            fd.append('permiso', perm);

            const res = await fetch('{{ route("permisos.remover") }}', {
                method: 'POST', body: fd
            });
            const { ok, permisos } = await res.json();
            if (ok) 
            {
                // repinta lista dentro del modal
                listaPermisosUsuario.innerHTML = permisos.length
                ? permisos.map(p => `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${p}
                        <i class="fa fa-times text-danger"
                        style="cursor:pointer"
                        data-perm-remove="${p}"></i>
                    </li>`).join('')
                : `<li class="list-group-item text-muted">Sin permisos directos</li>`;

                // repinta badges en la tabla
                actualizarPermisosBadges(uid, permisos);
            }
        });


        // ─── 3) DELEGAR click ❌ badge Permiso en TABLA ───────
        usuariosTbody.addEventListener('click', e => {
            const btn = e.target.closest('[data-permiso-remove]');
            if (!btn) return;

            const tr       = btn.closest('tr');
            const uid      = tr.dataset.id;
            const username = tr.dataset.username;
            const permis   = btn.dataset.permisoRemove;

            permisoRemoveUid.value     = uid;
            permisoRemovePerm.value    = permis;
            permisoRemoveText.innerHTML = `¿Quitar el permiso <strong>${permis}</strong> del usuario <strong>${username}</strong>?`;
            permisoRemoveModal.show();
        });

        // 3) DELEGAR click ❌ badge Rol en MODAL de Roles
        listaRolesUsuario.addEventListener('click', async e => {
            const btn = e.target.closest('[data-rol-remove-modal]');
            if (!btn) return;

            const uid = rolAssignUid.value;
            const rolToRemove = btn.dataset.rolRemoveModal;

            // Llamada AJAX para quitar el rol
            const fd = new FormData();
            fd.append('usuario_id', uid);
            fd.append('rol', rolToRemove);
            fd.append('_token', csrfToken);

            const res = await fetch('{{ route("roles.remover") }}', {
                method: 'POST',
                body: fd
            });
            const { ok, roles } = await res.json();
            if (!ok) return;

            // 3a) Repinta badges en la tabla
            actualizarRolesBadges(uid, roles);

            // 3b) Quita el ítem del modal y actualiza el select
            btn.closest('li').remove();
            const faltantes = catalogoRoles.filter(r => !roles.includes(r));
            rolAssignSelect.innerHTML = faltantes
                .map(r => `<option value="${r}">${r}</option>`).join('');
        });


        // ─── 4) SUBMIT Quitar Permiso ────────────────────────
        permisoRemoveForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(permisoRemoveForm);
            fd.append('_token', csrfToken);

            const res = await fetch('{{ route("permisos.remover") }}', { method:'POST', body:fd });
            const { ok, permisos } = await res.json();
            if (ok) {
            actualizarPermisosBadges(permisoRemoveUid.value, permisos);
            permisoRemoveModal.hide();
            }
        });

        // ─── 5) ABRIR modal Roles ──────────────────────────────
        document.querySelectorAll('[data-btn-roles]').forEach(btn => {
            btn.addEventListener('click', async () => {
            const tr   = btn.closest('tr');
            const uid  = tr.dataset.id;
            const user = tr.dataset.username;

            rolAssignUid.value   = uid;
            rolAssignUserSpan.textContent = user;

            const res = await fetch(`/api/roles-usuario/${uid}`);
            const { roles, catalogo } = await res.json();

            // lista de roles en el modal
            rolListInModal.innerHTML = roles.length
                ? roles.map(r => `
                    <li class="list-group-item d-flex justify-content-between">
                    ${r}
                    <i class="fa fa-times text-danger"
                        style="cursor:pointer"
                        data-rol-remove-modal="${r}"></i>
                    </li>`).join('')
                : '<li class="list-group-item text-muted">Sin roles asignados</li>';

            // opciones faltantes
            const faltantes = catalogo.filter(r => !roles.includes(r));
            rolAssignSelect.innerHTML = faltantes.length
                ? faltantes.map(r => `<option value="${r}">${r}</option>`).join('')
                : `<option disabled>No hay roles disponibles</option>`;

            rolAssignModal.show();
            });
        });

        // ─── 6) DELEGAR click ❌ badge Rol en TABLA ────────────
        usuariosTbody.addEventListener('click', e => {
            const btn = e.target.closest('[data-rol-remove]');
            if (!btn) return;

            const tr       = btn.closest('tr');
            const uid      = tr.dataset.id;
            const username = tr.dataset.username;
            const rolName  = btn.dataset.rolRemove;

            rolRemoveUid.value    = uid;
            rolRemoveName.value   = rolName;
            rolRemoveText.innerHTML = `¿Quitar el rol <strong>${rolName}</strong> al usuario <strong>${username}</strong>?`;
            rolRemoveModal.show();
        });

        // ─── 7) SUBMIT Quitar Rol ─────────────────────────────
        rolRemoveForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(rolRemoveForm);
            fd.append('_token', csrfToken);

            const res = await fetch('{{ route("roles.remover") }}', { method:'POST', body:fd });
            const { ok, roles } = await res.json();
            if (ok) {
            actualizarRolesBadges(rolRemoveUid.value, roles);
            rolRemoveModal.hide();
            }
        });

        // ─── 8) SUBMIT Asignar Rol ────────────────────────────
        rolAssignForm.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(rolAssignForm);
            fd.append('_token', csrfToken);

            const res = await fetch('{{ route("roles.asignar") }}', { method:'POST', body:fd });
            const { ok, roles } = await res.json();
            if (ok) {
            actualizarRolesBadges(rolAssignUid.value, roles);
            rolAssignModal.hide();
            }
        });

        // ─── Helpers para repintar badges ─────────────────────
        function actualizarPermisosBadges(uid, list) {
            const tr = document.querySelector(`tr[data-id="${uid}"]`);
            const td = tr.querySelector('[data-permisos]');
            td.innerHTML = list.length
            ? list.map(p => `
                <span class="badge bg-secondary me-1">
                    ${p}
                    <i class="fa fa-times ms-1 small"
                    style="cursor:pointer"
                    data-permiso-remove="${p}"></i>
                </span>`).join('')
            : '—';
        }

        function actualizarRolesBadges(uid, list) {
            const tr = document.querySelector(`tr[data-id="${uid}"]`);
            const td = tr.querySelector('[data-roles]');
            td.innerHTML = list.length
            ? list.map(r => `
                <span class="badge bg-warning text-dark me-1">
                    ${r}
                    <i class="fa fa-times ms-1 small"
                    style="cursor:pointer"
                    data-rol-remove="${r}"></i>
                </span>`).join('')
            : '—';
        }
        });
    </script>

    <script>
        const rutaSyncPermisos = '{{ url("roles") }}/' + rolId + '/permisos';
        await fetch(rutaSyncPermisos, { method:'POST', … });
    </script>


    <script>
        //Gestión de roles y permisos de roles
        document.addEventListener('DOMContentLoaded',()=>{
        // referencias
        const modalRoles   = new bootstrap.Modal('#modalRoles');
        const tablaRoles   = document.querySelector('#tablaRoles tbody');
        const inputNewName = document.getElementById('nuevoRoleName');
        const btnCrear     = document.getElementById('btnCrearRole');

        const rolSelected  = { id:null, name:null };
        const spanRolName  = document.getElementById('rolSelectedName');
        const listaPerms   = document.getElementById('listaPermisosRol');
        const btnGuardar   = document.getElementById('btnSyncPermisos');

        let catalogoPerms = [];

        // Cargar modal
        document.querySelector('[data-bs-target="#modalRoles"]')
            .addEventListener('click', async()=>{
            // 1) Traer roles
            const res = await fetch('{{ route("api.roles") }}');
            const roles = await res.json();

            // 2) Render tabla
            tablaRoles.innerHTML = roles.map(r=>`
                <tr data-id="${r.id}">
                <td>${r.name}</td>
                <td>${r.users_count}</td>
                <td>${r.perms_count}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary"
                            data-action="select">
                    <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger"
                            data-action="delete" 
                            ${r.users_count>0 ? 'disabled title="Asignado a usuarios"' : ''}
                            >
                    <i class="fa fa-trash"></i>
                    </button>
                </td>
                </tr>`
            ).join('');

            // 3) Traer catálogo de permisos una sola vez
            @if(!isset($catalogoPerms))
            catalogoPerms = @json(\Spatie\Permission\Models\Permission::pluck('name'));
            @endif

            modalRoles.show();
        });

        // Delegación sobre la tabla de roles
        tablaRoles.addEventListener('click', async e=>{
            const btn = e.target.closest('button');
            if(!btn) return;

            const tr = btn.closest('tr');
            const id = tr.dataset.id;
            const act= btn.dataset.action;

            // Eliminar rol
            if(act==='delete'){
            if(!confirm('¿Seguro eliminar rol?')) return;
            const res = await fetch(`/roles/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}' }});
            if(res.status===409){
                const {message} = await res.json();
                return alert(message);
            }
            if(res.ok) tr.remove();
            return;
            }

            // Seleccionar rol para editar permisos
            if(act==='select'){
            rolSelected.id   = id;
            rolSelected.name = tr.querySelector('td').textContent.trim();
            spanRolName.textContent = rolSelected.name;

            // Cargar permisos actuales del rol
            const resp = await fetch(`/api/roles/${id}/permisos`);
            const {all, has} = await resp.json();

            // Pintar lista con checkboxes
            listaPerms.innerHTML = all.map(p=>`
                <li class="list-group-item">
                <label class="form-check-label w-100">
                    <input type="checkbox" class="form-check-input me-2"
                        value="${p}"
                        ${has.includes(p)?'checked':''} >
                    ${p}
                </label>
                </li>`).join('');
            }
        });

        // Crear nuevo rol
        btnCrear.addEventListener('click', async()=>{
            const name = inputNewName.value.trim();
            if(!name) return alert('Escribe un nombre');
            const fd = new FormData(); fd.append('name',name); fd.append('_token','{{ csrf_token() }}');
            const res= await fetch('{{ route("roles.store") }}',{method:'POST',body:fd});
            const {ok,role} = await res.json();
            if(ok) {
            inputNewName.value='';
            // agrega al final de la tabla
            tablaRoles.insertAdjacentHTML('beforeend',`
                <tr data-id="${role.id}">
                <td>${role.name}</td><td>0</td><td>0</td>
                <td>…</td>
                </tr>`);
            }
        });

        // Guardar permisos editados
        btnGuardar.addEventListener('click', async()=>{
            const checks = [...listaPerms.querySelectorAll('input:checked')].map(i=>i.value);
            const fd     = new FormData(); fd.append('_token','{{ csrf_token() }}');
            checks.forEach(p=>fd.append('permisos[]',p));
            const res = await fetch(`/roles/${rolSelected.id}/permisos`,{ method:'POST', body:fd });
            const {ok} = await res.json();
            if(ok) alert('Permisos actualizados');
            // opcional: refrescar el conteo en la tabla de roles
        });
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', () => {
  const csrf     = document.querySelector('meta[name="csrf-token"]').content;
  const btnOpen  = document.getElementById('btnCatalogoPermisos');
  const modal    = new bootstrap.Modal('#modalCatalogoPermisos');
  const tblBody  = document.querySelector('#tablaPermisosCatalogo tbody');

  const inpNew   = document.getElementById('nuevoPermName');
  const btnCreate= document.getElementById('btnCrearPermiso');

  const spanName = document.getElementById('permCatSelectedName');
  const listRoles= document.getElementById('listaRolesConPermiso');

  // 1) Abrir modal y listar permisos
  btnOpen.addEventListener('click', async () => {
    const res   = await fetch('{{ route("permisos.catalogo.index") }}');
    const perms = await res.json();

    tblBody.innerHTML = perms.map(p=>`
      <tr data-id="${p.id}">
        <td>${p.name}</td>
        <td>${p.roles_count}</td>
        <td>
          <button data-action="select" class="btn btn-sm btn-outline-primary">
            <i class="fa fa-eye"></i>
          </button>
          <button data-action="delete" class="btn btn-sm btn-outline-danger"
                  ${p.roles_count>0?'disabled title="Asignado a roles"':''}>
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>`).join('');

    spanName.textContent = '–';
    listRoles.innerHTML = '';
    modal.show();
  });

  // 2) Crear permiso
  btnCreate.addEventListener('click', async () => {
    const name = inpNew.value.trim();
    if (!name) return alert('Escribe un nombre de permiso');
    const fd = new FormData(); fd.append('name',name); fd.append('_token',csrf);

    const res = await fetch('{{ route("permisos.catalogo.store") }}',{
      method:'POST',body:fd
    });
    if (!res.ok) return alert('Error al crear permiso');
    const { perm } = await res.json();

    tblBody.insertAdjacentHTML('beforeend', `
      <tr data-id="${perm.id}">
        <td>${perm.name}</td>
        <td>0</td>
        <td>
          <button data-action="select" class="btn btn-sm btn-outline-primary">
            <i class="fa fa-eye"></i>
          </button>
          <button data-action="delete" class="btn btn-sm btn-outline-danger">
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>`);
    inpNew.value = '';
  });

  // 3) Delegación en la tabla
  tblBody.addEventListener('click', async e => {
    const btn = e.target.closest('button');
    if (!btn) return;

    const tr  = btn.closest('tr');
    const id  = tr.dataset.id;
    const act = btn.dataset.action;

    if (act === 'select') {
      spanName.textContent = tr.children[0].textContent;
      // opcional: listar roles que tienen el permiso
      // Omitimos fetch aquí si no lo necesitas
      listRoles.innerHTML = `<li class="list-group-item">
        Roles que usan: ${tr.children[1].textContent}
      </li>`;
      return;
    }

    if (act === 'delete') {
      if (!confirm('¿Seguro que deseas eliminar este permiso?')) return;
      const url = '{{ route("permisos.catalogo.destroy",["permission"=>0]) }}'.replace('/0','/'+id);
      const res = await fetch(url, {
        method:'DELETE',
        headers:{ 'X-CSRF-TOKEN':csrf }
      });
      if (res.status === 409) {
        const { message } = await res.json();
        return alert(message);
      }
      if (!res.ok) return alert('Error al eliminar permiso');
      tr.remove();
      if (spanName.textContent === tr.children[0].textContent) {
        spanName.textContent = '–';
        listRoles.innerHTML = '';
      }
    }
  });
});
</script>

@endpush




@endsection
