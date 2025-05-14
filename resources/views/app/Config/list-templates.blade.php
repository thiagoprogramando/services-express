@extends('app.layout')
@section('content')

    <div class="col-12">
        <label class="kanban-add-board-btn" for="kanban-add-board-input">
            <a href="{{ route('create-templates') }}">
                <i class="ri-add-line"></i>
                <span class="align-middle">Novo Template</span>
            </a>
        </label>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Detalhes</th>
                            <th class="text-center">T. Ordens</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($templates as $template)
                            <tr>
                                <td>
                                    <i class="ri-slideshow-line ri-22px text-{{ $template->labelStatus()['color'] }} mr-4"></i>
                                    <span class="fw-medium">{{ $template->name }}</span>
                                    <br>
                                    <span class="badge bg-label-info m-1">{{ $template->labelStatus()['status'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium">10</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('deleted-template') }}" method="POST" class="demo-inline-spacing delete">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $template->uuid }}">
                                        <button type="submit" class="btn btn-icon btn-outline-danger waves-effect" title="Deletar">
                                            <span class="tf-icons ri-delete-bin-line ri-22px"></span>
                                        </button>
                                        <a href="{{ route('view-template', ['uuid' => $template->uuid]) }}" class="btn btn-icon btn-outline-success waves-effect" title="Editar">
                                            <span class="tf-icons ri-eye-line ri-22px"></span>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection