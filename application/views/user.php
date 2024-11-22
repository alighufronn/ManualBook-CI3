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
                            data-target="#editUser"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- add user modal -->
<form action="<?= site_url('edit-user/') ?>">
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
              <div class="form-group">
                <label for="" class="text-sm">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username">
              </div>

              <div class="form-group">
                <label for="" class="text-sm">Password</label>
                <input type="password" name="password" class="form-control" placeholder="New Password">
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
    // $(document).ready(function() {
    //     $('#btnEdit').on('click', function() {
    //         var data = $(this).closest('tr');


    //     })
    // })
</script>