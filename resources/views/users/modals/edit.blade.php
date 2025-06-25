<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Редактировать пользователя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST" enctype="multipart/form-data" @if(isset($user))
                action="{{ route('users.update', $user) }}"
            @endif >
                @csrf
                <input type="hidden" id="user_id" name="user_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">ФИО</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required value="{{$user->full_name ?? ''}}">
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Дата рождения</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required value="{{$user->date_of_birth ?? ''}}">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required value="{{$user->phone ?? ''}}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Почта</label>
                        <input type="email" class="form-control" id="email" name="email" required value="{{$user->email ?? ''}}">
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" class="form-control" id="login" name="login" required value="{{$user->login ?? ''}}">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Новый пароль (оставьте пустым чтобы сохранить текущий)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Фото</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
    });
</script>