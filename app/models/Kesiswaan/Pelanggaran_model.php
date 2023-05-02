<?php

require_once dirname(dirname(__DIR__)) . '/controllers/login/Login.php';
class Pelanggaran_model extends Database
{
    private $table = 'datapelanggaran';
    private $user;
    private $fields = [
        'NIS',
        'namaPelanggar',
        'namaDataPelanggaran',
        'poinDataPelanggaran'
    ];
    private $db;

    public function __construct()
    {
        $this->db = new Database(DB_KESISWAAN);
        $this->user = Login::getCurrentSession()->username;
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


    public function getPelanggaranById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->fetch();
    }

    public function tambahDataPelanggaran($data)
    {                     //nama tabel
        $query = "INSERT INTO " . $this->table . " VALUES(
            null, :uuid, :NIS, :namaPelanggar, :namaDataPelanggaran, :poinDataPelanggaran, 100, DEFAULT, '', CURRENT_TIMESTAMP, :created_by, null, '', null, '', null, '', 0, 0, DEFAULT)";

        $this->db->query($query);
        $this->db->bind('uuid', '8');
        foreach ($this->fields as $field) {
            $this->db->bind($field, $data[$field]);
        }
        $this->db->bind('created_by', $this->user);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function hapusDataPelanggaran($id)
    {
        $this->db->query(
            "UPDATE {$this->table}  
                SET 
                deleted_at = CURRENT_TIMESTAMP,
                deleted_by = :deleted_by,
                is_deleted = 1
            WHERE id = :id"
        );

        $this->db->bind('deleted_by', $this->user);
        $this->db->bind("id", $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataPermanen($id)
    {
        $this->db->query(
            "DELETE FROM {$this->table} WHERE id = :id"
        );

        $this->db->bind("id", $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahDataPelanggaran($data)
    {                     //nama tabel
        $query = "UPDATE datapelanggaran SET
                    NIS = :NIS,
                    namaPelanggar = :namaPelanggar,
                    namaDataPelanggaran = :namaDataPelanggaran,
                    poinDataPelanggaran = :poinDataPelanggaran,
                    modified_at = CURRENT_TIMESTAMP,
                    modified_by = :modified_by
                    WHERE id = :id";

        $this->db->query($query);
        foreach ($this->fields as $field) {
            $this->db->bind($field, $data[$field]);
        };
        $this->db->bind('modified_by', $this->user);
        $this->db->bind('id', $data['id']);

        $this->db->execute();

        return $this->db->rowCount();
    }
}
