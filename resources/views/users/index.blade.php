@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Добавить пользователя
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Фото</th>
                    <th>ФИО</th>
                    <th>Дата рождения</th>
                    <th>Телефон</th>
                    <th>Почта</th>
                    <th>Логин</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" width="50" class="rounded-circle">
                            @else
                                <div class="no-photo">Не заданно</div>
                            @endif
                        </td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->date_of_birth}}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->login }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                    data-user-id="{{ $user->id }}"
                                    data-full-name="{{ $user->full_name }}"
                                    data-date-of-birth="{{ $user->date_of_birth }}"
                                    data-phone="{{ $user->phone }}"
                                    data-email="{{ $user->email }}"
                                    data-login="{{ $user->login }}">
                                Редактировать
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                    data-user-id="{{ $user->id }}"
                                    data-full-name="{{ $user->full_name }}">
                                Удалить
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('users.modals.add')
    @include('users.modals.edit')
    @include('users.modals.delete')

@endsection

@section('scripts')
    <script>
        // Initialize edit modal with users data
        $('#editUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#user_id').val(button.data('users-id'));
            modal.find('#full_name').val(button.data('full-name'));
            modal.find('#date_of_birth').val(button.data('date-of-birth'));
            modal.find('#phone').val(button.data('phone'));
            modal.find('#email').val(button.data('email'));
            modal.find('#login').val(button.data('login'));
        });

        // Initialize delete modal with users data
        $('#deleteUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('#user_id').val(button.data('users-id'));
            modal.find('.users-name').text(button.data('full-name'));
        });
    </script>
@endsection