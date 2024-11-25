<style>
    .cardHalaman { 
        border: 1.5px solid #007BFF; 
        transition: background-color 0.3s, color 0.3s; 
        position: relative; 
        overflow: hidden;
    } 
    .cardHalaman::before { 
        content: ''; 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background-color: #007BFF;
        opacity: 80%;
        z-index: 1; 
        transition: transform 0.3s; 
        transform: scaleX(0); 
        transform-origin: left; 
    } 
    .cardHalaman:hover::before { 
        transform: scaleX(1); 
    } 
    .cardHalaman:hover {
        opacity: 100%; 
        color: white; 
    } 
    .cardHalaman > * { 
        position: relative; 
        z-index: 2;
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4 mb-3">
                <select name="" id="pilihKategori" class="form-control select2 namaKategori" style="width: 100%;">
                    <option value="">-- Filter Kategori --</option>
                    <?php 

                    $lainLain = array_filter($kategoris, function($kategori) {
                        return $kategori['id'] === '15';
                    });
                    $otherKategori = array_filter($kategoris, function($kategori) {
                        return $kategori['id'] !== '15';
                    });
                    $kategoris = array_merge($lainLain, $otherKategori);

                    foreach($kategoris as $kategori): ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($logged_in): ?>
            <div class="col-sm-8">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#kategoriModal" title="Tambah Kategori"><i class="fas fa-plus"></i></button>

                <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#halamanModal">Tambah Halaman</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <?php foreach($kategoris as $kategori): ?>
            <?php
            $hasManualBook = false;
            foreach ($manualbooks as $mb){
                if(in_array($kategori['nama'], $mb['kategori_list'])) {
                    $hasManualBook = true;
                    break;
                }
            }
            ?>
            <?php //if ($hasManualBook): ?>
            <div class="listItem" data-kategori="<?= $kategori['id'] ?>">
                <div class="mb-3">
                    <h5 class="text-bold text-uppercase kategori-text"><?= $kategori['nama'] ?></h5>
                </div>
                <div class="row listHalaman mb-5">
                    <?php 

                    foreach($manualbooks as $mb): ?>
                        <?php if (in_array($kategori['nama'], $mb['kategori_list'])): ?>
                        <div class="col-md-3 col-sm-4 col-6">
                            <a href="<?php echo site_url('detail/' . $mb['id']) ?>">
                                <div class="card cardHalaman shadow">
                                    <div class="card-body text-center text-bold">
                                        <?= $mb['nama_halaman'] ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php //endif; ?>
        <?php endforeach; ?>
    </div>
</div>


<?php if($logged_in): ?>
<!-- Kategori Modal -->
    <form action="<?= site_url('save-kategorimb') ?>" method="POST">
    <div class="modal fade" id="kategoriModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-bold">Buat Kategori</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-4">
                    <input type="text" name="nama" id="" class="form-control" placeholder="Nama Kategori">
                </div>

                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-hover table-striped">
                        <thead class="sticky-top shadow">
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($kategoris as $kat): ?>
                                <tr>
                                    <td><?= $kat['nama'] ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('delete-kategori/' . $kat['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus Kategori ini?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
    </div>
</form>
<!-- End Kategori Modal -->
<?php endif; ?>

<?php if($logged_in): ?>
<!-- Halaman Modal -->
<form action="<?= site_url('save-book') ?>" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="halamanModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-bold">Buat Halaman</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <!-- Nama & ID Kategori -->
                            <label for="">Kategori</label>
                            <select class="select2" id="selectKategori" multiple="multiple" data-placeholder="Pilih Kategori" style="width: 100%;" required>
                                <?php foreach($kategoris as $kategori): ?>
                                    <option value="<?= $kategori['id'] ?>">
                                        <?= $kategori['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" id="selectNamaKategori" name="nama_kategori" hidden>
                            <input type="text" id="selectIdKategori" name="id_kategori" hidden>
        
                            <!-- <input type="text" name="nama_kategori" id="namaKategori" class="form-control mb-3 namaKategori" value="" readonly>
                            <input type="text" class="idKategori" name="id_kategori" id="idKategori" hidden> -->
                        </div>
                    </div>
                    
    
                    <div class="col-md-5">
                        <div class="form-group">
                            <!-- Nama & ID Halaman -->
                            <label for="">Nama</label>
                            <input type="text" name="nama_halaman" id="namaHalaman" class="form-control mb-3" placeholder="Nama Halaman" required>
                            <input type="text" class="" name="id_halaman" id="" value="" hidden>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" style="opacity: 0%;">Tambah Gambar</label>
                            <button type="button" id="addImage" class="btn btn-primary w-100" id="addImage">Tambah Gambar</button>
                        </div>
                    </div>
               </div>

                <!-- Gambar -->
                <!-- <div class="form-group">
                    <label for="">Pilih Gambar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="gambar[]" multiple>
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div> -->
                
                <div class="table-responsive" style="max-height: 400px;">
                    <table id="tableImage" class="table table-striped table-hover table-sm text-sm">
                        <thead class="sticky-top shadow-sm">
                            <tr>
                                <th style="width: 20%; text-align: center;">Gambar</th>
                                <th style="width: 60%;"></th>
                                <th class="text-center table-bordered" style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
    </div>
</form>
<!-- End Halaman Modal -->
<?php endif; ?>



<!-- Script PageBook -->
<script>
    $(document).ready(function() { 
        $('.select2').select2(); 
    });
</script>

<script>
    $(document).ready(function() { 
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

        // Add Image row
        $('#addImage').on('click', function() {
            let table = $('#tableImage tbody');
            let rowCount = table.find('tr').length + 1;
            let newId = 'customFile' + rowCount;
            let newRow = `
                <tr>
                    <td class="text-center" style="min-width: 100px;"> 
                        <img class="img-hover" src="#" id="img-preview` + rowCount + `" style="aspect-ratio: 16/9; object-fit: contain; background-color: black; width: 100%;"> 
                    </td> 
                    <td class="align-middle"> 
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

        // Auto input
        $('#selectKategori').on('change', function() {
            var selectedCategories = $(this).val().map(function(item) {
                return $.trim(item);
            });
            var selectedTextArray = $(this).find('option:selected').map(function() {
                return $.trim($(this).text());
            }).get();

            var selectedText = selectedTextArray.join(',');
            var selectedValue = selectedCategories.join(',');

            $('#selectNamaKategori').val(selectedText);
            $('#selectIdKategori').val(selectedValue);
        });

        // Load saved category from local storage 
        var savedCategory = localStorage.getItem('selectedCategory'); 
        var savedText = localStorage.getItem('selectedText'); 
        if (savedCategory) { 
            $('#pilihKategori').val(savedCategory).change(); 
            $('#idKategori').val(savedCategory); 
            $('#namaKategori').val(savedText); 

            $('.listItem').each(function() {
                var itemKategori = $(this).data('kategori')
                if (savedCategory === "" || itemKategori == savedCategory) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Filter
        $('#pilihKategori').on('change', function() {
            var selectedCategory = $(this).val();
            var selectedText = $(this).find('option:selected').text();

            $('.listItem').each(function() {
                var itemKategori = $(this).data('kategori');
                if (selectedCategory === "" || itemKategori == selectedCategory) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            $('#idKategori').val(selectedCategory);
            $('#namaKategori').val(selectedText);

            // Save to local storage 
            localStorage.setItem('selectedCategory', selectedCategory); 
            localStorage.setItem('selectedText', selectedText);
        });
        

        // Alert
        


        // Multiple Image
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = '';
            for (var i = 0; i < this.files.length; i++) {
                fileName += this.files[i].name + ', ';
            }
            fileName = fileName.slice(0, -2); // Remove the last comma and space
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
            
            // Display file names in a separate div
            var fileList = document.getElementById('fileList');
            fileList.innerHTML = '<strong>Selected files:</strong> ' + fileName;
        });
    });
</script>
<!-- End Script PageBook -->