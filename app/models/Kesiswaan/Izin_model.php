<?php

use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Izin_model
{
    private $table = 'keterangan__izins';
    private $fields = [
        'ID_KEHADIRAN',
        'KETERANGAN',
        'STATUSS'
    ];

    private $user;
    private $db;

    public function __construct()
    {
        $this->db = new Database(DB_KESISWAAN);
        $this->user = Cookie::get_jwt()->name;
    }

    public function getAllData()
    {
        $this->db->query("SELECT * FROM {$this->table}");
        return $this->db->fetchAll();
    }

    public function getAllExistData()
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE `status` = 1");
        return $this->db->fetchAll();
    }

    public function getAllDeletedData()
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE `status` = 0");
        return $this->db->fetchAll();
    }


    public function getIzinById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->fetch();
    }

    public function tambahDataIzin($data)
    {                     //nama tabel
        $query = "INSERT INTO keterangan__izins VALUES(
            null, :uuid, :ID_KEHADIRAN, :KETERANGAN, :STATUSS, :note, CURRENT_TIMESTAMP, :created_by, null, '', null, '', null, '', 0, 0, DEFAULT)";

        $this->db->query($query);
        $this->db->bind('uuid', Uuid::uuid4()->toString());
        foreach ($this->fields as $field) {
            $this->db->bind($field, $data[$field]);
        }
        if ($data['note'] != '') {
            $this->db->bind('note', $data['note']);
        } else {
            $this->db->bind('note', '');
        }

        $this->db->bind('created_by', $this->user);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function hapusDataIzin($id)
    {
        $this->db->query(
            "UPDATE {$this->table}  
                SET 
                deleted_at = CURRENT_TIMESTAMP,
                deleted_by = :deleted_by,
                is_deleted = 1,
                is_restored = 0
            WHERE id = :id"
        );

        $this->db->bind('deleted_by', $this->user);
        $this->db->bind("id", $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahDataIzin($data)
    {                     //nama tabel
        $query = "UPDATE keterangan__izins SET
                    ID_KEHADIRAN = :ID_KEHADIRAN,
                    KETERANGAN = :KETERANGAN,
                    STATUSS = :STATUSS,
                    modified_at = CURRENT_TIMESTAMP,
                    modified_by = :modified_by
                    WHERE id = :id";

        $this->db->query($query);
        foreach ($this->fields as $field) {
            $this->db->bind($field, $data[$field]);
        }
        $this->db->bind('modified_by', $this->user);
        $this->db->bind('id', $data['id']);


        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getHistory($nisn)
    {
        $this->db->query(
            "SELECT * FROM {$this->table}
                WHERE 
            `ID_KEHADIRAN` = :nisn
        "
        );

        $this->db->bind("nisn", $nisn);

        return $this->db->fetchAll();
    }

    public function check($nisn)
    {
        // Mendapatkan rentang waktu untuk satu hari (mulai dan akhir hari saat ini)
        $now = date('Y-m-d H:i:s');
        $startOfDay = date('Y-m-d 00:00:00', strtotime($now));
        $endOfDay = date('Y-m-d 23:59:59', strtotime($now));

        // Mengecek apakah ada data dengan NISN yang sama dalam rentang waktu sehari
        $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE ID_KEHADIRAN = :nisn AND created_at BETWEEN :startOfDay AND :endOfDay");
        $this->db->bind('nisn', $nisn);
        $this->db->bind('startOfDay', $startOfDay);
        $this->db->bind('endOfDay', $endOfDay);

        $this->db->execute();
        $count = $this->db->fetch()['COUNT(*)'];
        return $count;
    }

    public function importData()
    {
        // Cek file diupload apa belum
        if (!isset($_FILES['file']['name'])) {
            Flasher::setFlash('Error', 'Harap pilih file Excel terlebih dahulu', 'danger');
            header('location: ' . BASEURL . '/izin');
            exit;
        }

        // Baca file Excel menggunakan PhpSpreadsheet
        $inputFileName = $_FILES['file']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileName);

        // Ambil data dari sheet pertama
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $maxColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        // Daftar kolom yang akan diambil dari file Excel dan disimpan ke database
        $columns = $this->fields;

        // Looping untuk membaca setiap baris data
        for ($row = 2; $row <= $highestRow; $row++) {
            $data = [];

            // Looping untuk membaca setiap kolom data
            for ($col = 2; $col <= count($columns) + 1; $col++) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);
                $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
                $data[$columns[$col - 2]] = $cellValue;
            }

            // Simpan data ke database
            $response = $this->tambahDataIzin($data);
        }
        return $response;
    }
}
