<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Подтвердите удаление</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
{{--            {{dd($user)}}--}}
            <form id="deleteUserForm" method="POST" action="{{ route('users.delete', $user ?? 1) }}">
                @csrf
                <input type="hidden" id="user_id" name="user_id">

                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить пользователя?<strong class="user-name"></strong>?</p>
                    <p class="text-danger">Это действие нельзя отменить.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#deleteUserModal').on('show.bs.modal', function (event) {
        var userId = $(event.relatedTarget).data('user-id');
    });
</script>