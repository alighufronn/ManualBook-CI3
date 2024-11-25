<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUser">Tambah User</button>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped">
            <thead class="table-bordered">
                <tr>
                    <th style="width: 80%;">Username</th>
                    <th style="width: 20%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td id="getUsername"><?= $user['username'] ?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning" id="btnEdit" data-toggle="modal"
                            data-target="#editUser" data-id="<?= $user['id'] ?>"><i class="fas fa-edit"></i></button>
                            <a href="<?= site_url('users/delete-user/' . $user['id']) ?>" class="btn btn-danger" id="btnDelete" onclick="return confirm('Apakah anda yakin ingin menghapus User ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- edit user modal -->
<form action="<?= site_url('users/edit-user') ?>" method="POST">
    <div class="modal fade" id="editUser">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <label for="">Edit User</label>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="id" id="userIdEdit">
              <div class="form-group">
                <label for="" class="text-sm">Username</label>
                <input type="text" class="form-control" name="username" id="usernameEdit" placeholder="Username">
              </div>

              <div class="form-group">
                <label for="" class="text-sm">Password</label>
                <input type="password" name="password" id="passwordEdit" class="form-control" placeholder="New Password">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
    </div>
</form>

<!-- add User Modal -->
<form action="<?= site_url('users/add-user') ?>" method="POST">
    <div class="modal fade" id="addUser">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <label for="">Tambah User</label>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="" class="text-sm">Username</label>
                <input type="text" class="form-control" name="username" id="addUsername" placeholder="Username">
              </div>

              <div class="form-group">
                <label for="" class="text-sm">Password</label>
                <input type="password" name="password" id="addPassword" class="form-control" placeholder="New Password">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
    </div>
</form>


<script>
  $(document).ready(function() {
    $('#editUser').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('id');
        var modal = $(this);

        console.log("Modal opened, user ID: " + userId);

        $.ajax({
            url: '<?= site_url("AuthController/getUserData") ?>',
            method: 'GET',
            data: { id: userId },
            success: function(response) {
                console.log("Response received: ", response);
                var userData = JSON.parse(response);
                modal.find('input[name="id"]').val(userId);
                modal.find('input[name="username"]').val(userData.username);
                modal.find('input[name="password"]').val(''); 
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
  });
</script>