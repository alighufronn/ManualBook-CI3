<style>
    .lightbox-open {
        overflow: hidden;
    }
    .lightbox { 
        /* display: flex; */
        justify-content: center; 
        align-items: center;
    }

    /* .controlIcon:hover {
        color: #3C8DBC;
        filter: brightness(0.7);
    } */

    .carousel-indicators { 
        position: absolute; 
        bottom: -50px;
        z-index: 15;
    }
    .carousel-indicators .cIndicators {
        /* margin: 0 2.5px; */
        filter: brightness(0.5  );
        /* max-width: 100%; */
        /* width: 2.5vw; */
        /* background-color: black; */
        background-color: #007BFF;
        aspect-ratio: 16/9;
        object-fit: fill;
        transition: 0.3s;
    }
    .carousel-indicators .cIndicators.active {
        background-color: #007BFF;
        width: 60px;
        filter: brightness(1);
        /* border: 2px solid #007BFF; */
        /* object-fit: contain; */
    }
    
    .fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
        background-color: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fullscreen img {
        width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .carousel-img {
        object-fit: contain;
        background-color: black;
        width: 100%;
        aspect-ratio: 20/9;
    }

    .fullscreen #exitFullscreen {
        display: block;
        z-index: 2000;
        /* pointer-events: all; */
    }
</style>


<!-- Action Card -->
<?php if($logged_in): ?>
<div class="card">
    <div class="card-body">
        <!-- Hapus -->
        <a href="<?= site_url('delete-multi/' . $id_halaman) ?>" id="delete-multiple" class="btn btn-danger text" onclick="return confirm('Apakah yakin untuk menghapus Halaman ini?');">Hapus Halaman</a>

        <!-- Edit -->
        <button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#modalImage">Edit Halaman</button>
    </div>
</div>
<?php endif; ?>
<!-- End Action Card -->

<!-- Carousel -->
<div id="demo" class="carousel slide shadow mb-5" data-ride="carousel">
    <ul class="carousel-indicators fixed-bottom">
        <?php $index = 0; ?>
        <?php foreach($gambar as $img): ?>
            <li data-target="#demo" data-slide-to="<?= $index ?>" class="cIndicators <?= $index === 0 ? 'active' : '' ?>"></li>
            <?php $index++; ?>
        <?php endforeach; ?>
    </ul>
                
    <div class="carousel-inner">
    <?php if (!empty($gambar)): ?>
        <?php $index = 0; ?>
        <?php foreach($gambar as $img): ?>
            <?php $filePath = FCPATH . 'assets/uploads/img/' . $img['gambar']; ?>
            <?php if (file_exists($filePath)): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= base_url('assets/uploads/img/' . $img['gambar']) ?>" class="carousel-img" loading="lazy">
                </div>
                <?php $index++; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>

    <a class="carousel-control-prev controlIcon" href="#demo" data-slide="prev">
        <i class="fas fa-arrow-left text-default"></i>
    </a>
    <a class="carousel-control-next controlIcon" href="#demo" data-slide="next">
        <i class="fas fa-arrow-right text-default"></i>
    </a>

    <button id="exitFullscreen" class="btn btn-transparent btn-lg" style="position: fixed; top: 10px; right: 20px; display: none; z-index: 2001"><i class="fas fa-times text-white"></i></button> 
</div>
<!-- End Carousel -->


<!-- Edit Modal -->
<form action="<?= site_url('update/' . $mb['id']); ?>" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="modalImage">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-uppercase text-bold">Edit Halaman</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="text-sm">Kategori</label>
                                        <?php $kategori_list = explode(',', $mb['nama_kategori']); ?>
                                        <select name="" id="selectKategori" class="select2" multiple="multiple" style="width: 100%;">
                                            <?php foreach($kategoris as $kat): ?>
                                                <option value="<?= $kat['id'] ?>" <?php if(in_array($kat['nama'], $kategori_list)) echo 'selected'; ?>>
                                                    <?= $kat['nama'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <input type="text" class="form-control" id="id_kategori" name="id_kategori" value="<?= $mb['id_kategori'] ?>" readonly hidden>
                                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= $mb['nama_kategori'] ?>" readonly hidden>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="text-sm">Halaman</label>
                                        <input type="text" class="form-control" name="nama_halaman" value="<?= $mb['nama_halaman'] ?>">
                                        <input type="text" class="form-control" name="id_halaman" value="<?= $mb['id'] ?>" readonly hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-danger" id="deleteImg">Hapus Gambar</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-primary float-right" id="addImage">Tambah Gambar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table id="tableImage" class="table table-striped table-hover table-sm text-sm display" style="width: 100%;">
                            <thead class="sticky-top shadow-sm">
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="selectImgAll" value="option1">
                                            <label for="selectImgAll" class="custom-control-label"></label>
                                        </div>
                                    </th>
                                    <th style="width: 20%; text-align: center;">Gambar</th>
                                    <th style="width: 60%; text-align: center;"></th>
                                    <th class="text-center table-bordered" style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; ?>
                                <?php foreach($gambar as $img): ?>
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="selectImg<?= $index ?>" data-id="<?= $img['id'] ?>">
                                            <label for="selectImg<?= $index ?>" class="custom-control-label"></label>
                                        </div>
                                    </td>
                                    <td class="text-center" style="min-width: 100px;">
                                        <img class="img-hover" src="<?= base_url('assets/uploads/img/' . $img['gambar']) ?>" id="img-preview<?= $index ?>" loading="lazy" style="aspect-ratio: 16/9; object-fit: contain; background-color: black; width: 100%;">
                                    </td>
                                    <td class="align-middle" style="min-width: 200px;">
                                        <input type="text" name="id[]" value="<?= $img['id'] ?>" hidden>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile<?= $index ?>" name="gambar[]">
                                            <label class="custom-file-label" for="customFile<?= $index ?>"><?= $img['gambar'] ?></label>
                                      </div>
                                    </td>
                                    <td class="text-center table-bordered align-middle" style="min-width: 50px;">
                                        <a href="<?= site_url('delete-img/' . $img['id']) ?>" class="btn btn-danger btn-delete" title="Hapus" onclick="return confirm('Apakah yakin ingin menghapus Gambar ini?');">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $index++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- End Edit Modal -->




<!-- <div id="debug-message" style="display: none; background-color: #f9f9f9; padding: 10px; border: 1px solid #ccc;"></div> -->

<script>
    $(document).ready(function() {
        // Carousel settings
        $('#demo').carousel({
            wrap: false,
            interval: false,
        });
        
        $('.carousel-img').on('click', function() {
            $('#demo').addClass('fullscreen');
            $('#exitFullscreen').show();
        });

        $('#exitFullscreen').on('click', function(event) {
            event.stopPropagation();
            $('#demo').removeClass('fullscreen');
            $(this).hide();
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#demo').length && !$(event.target).is('#exitFullscreen')) {
                $('#demo').removeClass('fullscreen');
                $('#exitFullscreen').hide();

            }
        });

        $('#demo').on('slide.bs.carousel slid.bs.carousel', function() {
            if ($(this).hasClass('fullscreen')) {
                $(this).addClass('fullscreen');
                $('#exitFullscreen').show();
            }
        });
        
        // Image Preview
        function readURL(input, index) { 
            if (input.files && input.files[0]) {
                 var reader = new FileReader(); 
                 reader.onload = function(e) { 
                    $('#img-preview' + index).attr('src', e.target.result); 
                } 
                reader.readAsDataURL(input.files[0]); 
            } 
        } 
        
        // Display Image Name
        $('.custom-file-input').on('change', function() { 
            var index = $(this).attr('id').replace('customFile', ''); 
            readURL(this, index); 
            var fileName = $(this).val().split('\\').pop(); 
            $(this).next('.custom-file-label').html(fileName); 
        });

        // Add Image Row
        $('#addImage').on('click', function() {
            let table = $('#tableImage tbody');
            let rowCount = table.find('tr').length + 1;
            let newId = 'customFile' + rowCount;
            let newRow = `
                <tr style="background-color: #FFF1C5">
                    <td>
                        <div class="custom-control custom-checkbox" hidden>
                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="option1">
                            <label for="customCheckbox1" class="custom-control-label"></label>
                        </div>
                    </td>
                    <td class="text-center" style="min-width: 100px;"> 
                        <img class="img-hover" src="#" id="img-preview` + rowCount + `" style="aspect-ratio: 16/9; object-fit: contain; background-color: black; width: 100%;"> 
                    </td> 
                    <td class="align-middle" style="min-width: 200px;"> 
                        <input type="hidden" name="id_gambar[]" value=""> 
                        <div class="custom-file"> 
                            <input type="file" class="custom-file-input" id="` + newId + `" name="gambar[]"> 
                            <label class="custom-file-label" for="` + newId + `">Choose file</label> 
                        </div> 
                    </td>
                    <td class="text-center align-middle table-bordered" style="min-width: 50px;"> 
                        <button type="button" class="btn btn-danger" onclick="$(this).closest('tr').remove();"><i class="fas fa-times"></i></button> 
                    </td>
                </tr>`;
            table.append(newRow);

            $('#'+newId).on('change', function() {
                var index = $(this).attr('id').replace('customFile', '');
                readURL(this, index);
                    
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });

        // Select All
        $('#selectImgAll').prop('checked', false);
        $('#selectImgAll').change(function() {
            var checkboxes = $(this).closest('table').find(':checkbox');
            checkboxes.prop('checked', $(this).is(':checked'));
        });

        // Delete Checked
        $('#deleteImg').on('click', function() {
            var checked = $('#tableImage').find(':checkbox:checked');
            var idsToDelete = checked.map(function() {
                return $(this).data('id');
            }).get();

            if (idsToDelete.length > 0) {
                if (confirm('Apakah anda yakin ingin menghapus gambar yang dipilih?')) {
                    $.ajax({
                        url: '<?= site_url("delete-imgs") ?>',
                        type: 'POST',
                        data: { ids: idsToDelete },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                checked.closest('tr').remove();
                                toastr.success('Gambar berhasil dihapus');
                            } else {
                                toastr.error('Gagal menghapus gambar');
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error('Terjadi kesalahan:' + error);
                        }
                    });
                }
            } else {
                toastr.warning('Pilih gambar yang ingin dihapus');
            }
        });



        // Auto input
        $('#selectKategori').on('change', function() {
            var selectedValArray = $(this).val(). map(function(item) {
                return $.trim(item);
            });

            var selectedTextArray = $(this).find('option:selected').map(function() {
                return $.trim($(this).text());
            }).get();

            var selectedVal = selectedValArray.join(',');
            var selectedText = selectedTextArray.join(',');

            $('#id_kategori').val(selectedVal);
            $('#nama_kategori').val(selectedText);
        });
    });    
</script>

<script>
    
</script>
