@extends('layouts.app')

@section('title', 'Blog')

@section('toolbar')
    <div class="toolbar py-5 py-lg-15" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-white fw-bolder my-1 fs-3">📒 Blog</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-white opacity-75">
                        <a href="{{ url() }}" class="text-white text-hover-primary">{{ $_ENV['APP_NAME'] }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Administração</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-white opacity-75">Blog</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span style="color: #a1a5b7;font-size:13.975px;">Criar publicação</span>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-light-primary btn-sm w-100 min-w-150px"
                            id="kt_user_game_nick_update_submit" onclick="sendTemp()">
                            <span class="indicator-label">Publicar</span>
                            <span class="indicator-progress">publicando...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_blog_create" enctype="multipart/form-data">
                        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex flex-column mb-7 fv-row">
                                        <label for="" class="form-label">
                                            🖌️ Cover <span class="text-muted">(1920x1080px)</span>
                                        </label>
                                        <div class="image-input image-input-outline w-100" data-kt-image-input="true">
                                            <div class="image-input-wrapper w-100"
                                                style="background-size: cover;background-position: center;height: 205px!important;">
                                            </div>
                                            <label
                                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                data-bs-dismiss="click" title="Change avatar">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <input type="file" name="cover" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="avatar_remove" />
                                            </label>
                                            <span
                                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                data-bs-dismiss="click" title="Cancel avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <span
                                                class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                data-bs-dismiss="click" title="Remove avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="d-flex flex-column mb-7 fv-row col-6">
                                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                🏷️ Título
                                            </label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="title" placeholder="A manchete do seu artigo">
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-6">
                                            <label for="" class="form-label">📛 Subtítulo</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="subtitle" placeholder="O texto de apoio da manchete">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="d-flex flex-column mb-7 fv-row col-4">
                                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                📺 Vídeo
                                            </label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="video" placeholder="O ID de um vídeo do YouTube">
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-4">
                                            <label for="" class="form-label">👻 Categoria</label>
                                            <select class="form-select form-select-sm form-select-solid"
                                                data-control="select2" data-hide-search="true"name="category" required>
                                                <option value="1">Atualização</option>
                                                <option value="2">Notícia</option>
                                                <option value="3">Evento</option>
                                            </select>
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-4">
                                            <label for="" class="form-label">🌍 Servidor</label>
                                            <select class="form-select form-select-sm form-select-solid"
                                                data-control="select2" data-hide-search="true"name="server" required>
                                                <option value="0">Todos</option>
                                                @foreach ($servers as $server)
                                                    <option value="{{ $server->id }}">{{ $server->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="d-flex flex-column mb-7 fv-row col-4">
                                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                🦄 Autor
                                            </label>
                                            <select class="form-select form-select-sm form-select-solid"
                                                data-control="select2" name="author" required>
                                                <option value="0">Todos</option>
                                                @foreach ($users as $row)
                                                    <option value="{{ $row->id }}">{{ $row->first_name }}
                                                        {{ $row->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-4">
                                            <label for="" class="form-label">🧁 Status</label>
                                            <select class="form-select form-select-sm form-select-solid"
                                                data-control="select2" data-hide-search="true"name="status" required>
                                                <option value="post">Publicar</option>
                                                <option value="draft">Rascunho</option>
                                                <option value="trash">Lixo</option>
                                            </select>
                                        </div>
                                        <div class="d-flex flex-column mb-7 fv-row col-4">
                                            <label for="" class="form-label">📅 Data de publicação</label>
                                            <input type="text" class="form-control form-control-sm form-control-solid"
                                                name="post_at" value="{{ date('d/m/Y H:i:s') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label for="" class="form-label">Conteúdo</label>
                                    <textarea id="kt_docs_tinymce_plugins" name="content" class="tox-target"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
@endsection

@section('custom-js')
    <script src="{{ url() }}/assets/plugins/custom/tinymce/tinymce.bundle.js"></script>
    <script>
        function utf8_to_b64(str) {
            return window.btoa(unescape(encodeURIComponent(str)));
        }

        function b64_to_utf8(str) {
            return decodeURIComponent(escape(window.atob(str)));
        }

        tinymce.init({
            selector: "#kt_docs_tinymce_plugins",
            height: 132,
            entity_encoding: "raw",
            theme_advanced_resizing: true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor media"
            ],
        });



        function sendTemp() {
            var form = $('#form_blog_create');
            var data = new FormData();

            jQuery.each(jQuery('input[name="cover"]')[0].files, function(i, file) {
                data.append('cover[]', file);
            });

            jQuery.each(form.serializeArray(), function(i, file) {
                data.append(file.name, file.value);
            });

            data.append('content', tinymce.get('kt_docs_tinymce_plugins').getContent());
            axios.post(`${baseUrl}/api/admin/blog`, data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {}).catch(err => {})
        }
    </script>
@endsection
