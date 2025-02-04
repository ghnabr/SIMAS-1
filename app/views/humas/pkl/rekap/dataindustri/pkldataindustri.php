

  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">DATA INDUSTRI</h3>
            <h6 class="font-weight-normal mb-0"><a class="text-dark" href="<?= BASEURL; ?>/pkl/index"> Laman PKL</a> | <a class="text-dark" href="<?= BASEURL; ?>/pkl/rekap"> Rekapitulasi </a> | <span class="text-primary"> Data Industri </span></h6>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <?php Flasher::flash(); ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="template-demo">
          <button type="button" class="btn btn-primary tomboltambahdata1" data-toggle="modal" data-target="#formmodal">Tambah Data</button>
          <button type="button" class="btn btn-primary  tampilModalImport" data-toggle="modal" data-target="#modalImport">
            Import Data Dari Excel
          </button>
        </div>
      </div>
    </div>

    <div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="judulModallabel1">Tambah Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="<?= BASEURL; ?>/Pkl/tambahdataiind" method="post">


              <input type="hidden" name="id" id="id">



              <div class="form-group mb-3">
                <label for="kompetensikeahlian">Kompetensi Keahlian</label>
                <select class="form-control" id="kompetensikeahlian" name="kompetensikeahlian">
                  <option value="Tekhnik Grafika">Tekhnik Grafika</option>
                  <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                  <option value="Logistik">Logistik</option>
                  <option value="Perhotelan">Perhotelan</option>
                  <option value="Mekatronika">Mekatronika</option>
                  <option value="Desain Komunikasi Visual">DKV</option>
                  <option value="Tekhnik Komputer Jaringan">TKJ</option>
                </select>
                </div>


                <div class="form-group">
                  <label for="namaperusahaan">Nama Perusahaan</label>
                  <input type="text" class="form-control" id="namaperusahaan" name="namaperusahaan">
                </div>



                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <input type="text" class="form-control" id="alamat" name="alamat">
                </div>



                <div class="form-group">
                  <label for="kota">KOTA</label>
                  <input type="text" class="form-control" id="kota" name="kota">
                </div>

              </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary btn-fw" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
          </div>
          </form>
        </div>
      </div>
    </div>


  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive ">
            <table class="table table-hover" id="table">
              <thead>
                <tr>
                  <th>Aksi</th>
                  <th>Kompetensi Keahlian</th>
                  <th>Nama Perusahaan</th>
                  <th>Alamat</th>
                  <th>Kota</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($data['dta'] as $dta) {
                ?>
                  <tr>

                    <td>


                        <a class="badge badge-success tampildataubah1" data-url="<?= BASEURL ?>/PKL/ubahind/<?= $dta['id']; ?>" data-toggle="modal" data-target="#formmodal" data-id="<?= $dta['id'] ?>"><i class="mdi mdi-pencil"></i></a>

                        <a href="<?= BASEURL; ?>/pkl/hapusind/<?= $dta['id']; ?>" class="badge badge-danger" onclick="return confirm('Apakah anda sudah yakin?');"><i class="mdi mdi-delete"></i></a>
                    </td>


          <td>
            <?= $dta['kompetensikeahlian'] ?>
          </td>
          <td>
            <?= $dta['namaperusahaan'] ?>
          </td>
          <td>
            <?= $dta['alamat'] ?>
          </td>
          <td>
            <?= $dta['kota'] ?>
          </td>
          </tr>
        <?php
                }
        ?>
        </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
