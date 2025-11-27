<?php
class RestoranModel {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // --- MENU ---
    public function getAllMenu(){
        $stmt = $this->conn->prepare("
            SELECT m.*, km.nama_kategori 
            FROM menu m
            JOIN kategori_menu km ON m.id_kategori = km.id_kategori
            ORDER BY m.id_menu ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMenuStatistics(){
        $stats = ['total_menu'=>0, 'makanan'=>0, 'minuman'=>0, 'dessert'=>0, 'snack'=>0];
        $stmtTotal = $this->conn->prepare("SELECT COUNT(*) FROM menu");
        $stmtTotal->execute();
        $stats['total_menu'] = $stmtTotal->fetchColumn();

        $stmtCat = $this->conn->prepare("
            SELECT 
                SUM(CASE WHEN km.nama_kategori='Makanan' THEN 1 ELSE 0 END) AS makanan,
                SUM(CASE WHEN km.nama_kategori='Minuman' THEN 1 ELSE 0 END) AS minuman,
                SUM(CASE WHEN km.nama_kategori='Dessert' THEN 1 ELSE 0 END) AS dessert,
            SUM(CASE WHEN km.nama_kategori='Snack' THEN 1 ELSE 0 END) AS snack
            FROM menu m
            JOIN kategori_menu km ON m.id_kategori = km.id_kategori
        ");
        $stmtCat->execute();
        $res = $stmtCat->fetch(PDO::FETCH_ASSOC);
        $stats['makanan'] = $res['makanan'] ?? 0;
        $stats['minuman'] = $res['minuman'] ?? 0;
        $stats['dessert'] = $res['dessert'] ?? 0;
        $stats['snack'] = $res['snack'] ?? 0;

        return $stats;
    }

    public function insertMenu($nama, $id_kategori, $harga, $deskripsi, $status, $foto)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO menu (nama_menu, id_kategori, harga, deskripsi, status_ketersediaan, foto_menu)
            VALUES (:nama, :kategori, :harga, :deskripsi, :status, :foto)
        ");
    
        return $stmt->execute([
            ':nama'      => $nama,
            ':kategori'  => $id_kategori,
            ':harga'     => $harga,
            ':deskripsi' => $deskripsi,
            ':status'    => $status ? 'true' : 'false',  // penting!
            ':foto'      => $foto
        ]);
    }
    

public function getMenuById($id){
    $stmt = $this->conn->prepare("
        SELECT m.*, k.nama_kategori 
        FROM menu m
        JOIN kategori_menu k ON m.id_kategori = k.id_kategori
        WHERE m.id_menu = :id
    ");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

        // Kategori menu
        public function getAllKategori(){
            $stmt = $this->conn->prepare("SELECT * FROM kategori_menu ORDER BY nama_kategori ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Ambil menu berdasarkan kategori
        public function getMenuByKategori($id_kategori){
            $stmt = $this->conn->prepare("
                SELECT m.*, km.nama_kategori 
                FROM menu m
                JOIN kategori_menu km ON m.id_kategori = km.id_kategori
                WHERE m.id_kategori = :id_kategori
                ORDER BY m.nama_menu ASC
            ");
            $stmt->bindParam(':id_kategori', $id_kategori);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    // --- PESANAN ---
     public function getAllPesanan(){
        $stmt = $this->conn->prepare("
            SELECT p.*, m.nomor_meja, pel.nama AS nama_pelanggan
            FROM pesanan p
            JOIN meja m ON p.id_meja = m.id_meja
            JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
            ORDER BY p.id_pesanan ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailPesanan($id_pesanan){
        $stmt = $this->conn->prepare("
            SELECT m.nama_menu, dp.jumlah, dp.harga_satuan
            FROM detail_pesanan dp
            JOIN menu m ON dp.id_menu = m.id_menu
            WHERE dp.id_pesanan = :id
        ");
        $stmt->bindParam(':id', $id_pesanan);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function tambahPesanan($id_pelanggan, $id_meja, $id_server, $tanggal_pesanan, $total_harga, $status_orderan) 
    {
    try {

            $sql = "INSERT INTO pesanan 
                    (id_pelanggan, id_meja, id_server, tanggal_pesanan, total_harga, status_orderan)
                    VALUES (:p, :m, :s, :t, :total, :st)";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':p'     => $id_pelanggan,
                ':m'     => $id_meja,
                ':s'     => $id_server,
                ':t'     => $tanggal_pesanan,   // format: Y-m-d
                ':total' => $total_harga,       // numeric OK
                ':st'    => $status_orderan     // varchar OK
            ]);

        } catch (PDOException $e) {

            echo "<b>PostgreSQL Error:</b><br>";
            echo $e->getMessage();
            return false;
        }
    }



    // READ (sudah ada getAllPesanan)
        public function getPesananById($id) {
        $query = "SELECT * FROM pesanan WHERE id_pesanan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function updatePesanan($id, $id_pelanggan, $id_meja, $id_server, $status) {
        $stmt = $this->conn->prepare ("UPDATE pesanan SET
                    id_pelanggan = :p,
                    id_meja = :m,
                    id_server = :s,
                    status_orderan = :status
                WHERE id_pesanan = :id");

        $stmt = $this->conn->prepare($stmt);
        return $stmt->execute([
            ':id' => $id,
            ':p' => $id_pelanggan,
            ':m' => $id_meja,
            ':s' => $id_server,
            ':status' => $status
        ]);
    }

    // DELETE
    public function hapusPesanan($id) {

        // HAPUS ITEM PESANAN
        $stmt1 = "DELETE FROM detail_pesanan WHERE id_pesanan = :id";
        $stmt1 = $this->conn->prepare($stmt1);
        $stmt1->execute([':id' => $id]);

        // HAPUS PEMBAYARAN
        $stmt2 = "DELETE FROM pembayaran WHERE id_pesanan = :id";
        $stmt2 = $this->conn->prepare($stmt2);
        $stmt2->execute([':id' => $id]);

        // HAPUS PESANAN UTAMA
        $stmt3 = "DELETE FROM pesanan WHERE id_pesanan = :id";
        $stmt3 = $this->conn->prepare($stmt3);
        return $stmt3->execute([':id' => $id]);
    }

        public function getAllPelanggan()
    {
        $sql = "SELECT * FROM pelanggan ORDER BY id_pelanggan ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getPelangganById($id)
    {
        $sql = "SELECT * FROM pelanggan WHERE id_pelanggan = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


        public function tambahPelanggan($nama, $no_telp)
    {
        try {
            $sql = "INSERT INTO pelanggan (nama, no_telepon)
                    VALUES (:nama, :no_telp)";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':nama' => $nama,
                ':no_telp' => $no_telp
            ]);

        } catch (PDOException $e) {
            echo "<b>PostgreSQL Error:</b><br>" . $e->getMessage();
            return false;
        }
    }

        public function hapusPelanggan($id)
    {
        try {
            $sql = "DELETE FROM pelanggan WHERE id_pelanggan = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo "<b>PostgreSQL Error:</b><br>" . $e->getMessage();
            return false;
        }
    }

     public function getAllMeja()
    {
        $query = "SELECT * FROM meja ORDER BY id_meja ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMejaById($id)
    {
        $query = "SELECT * FROM meja WHERE id_meja = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahMeja($nomor_meja, $status_meja, $kapasitas)
    {
        $query = "INSERT INTO meja (nomor_meja, status_meja, kapasitas) 
                  VALUES (:nomor_meja, :status_meja, :kapasitas)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nomor_meja", $nomor_meja);
        $stmt->bindParam(":status_meja", $status_meja);
        $stmt->bindParam(":kapasitas", $kapasitas);

        return $stmt->execute();
    }

    public function updateMeja($id, $nomor_meja, $status_meja, $kapasitas)
    {
        $query = "UPDATE meja 
                  SET nomor_meja = :nomor_meja,
                      status_meja = :status_meja,
                      kapasitas = :kapasitas
                  WHERE id_meja = :id";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nomor_meja", $nomor_meja);
        $stmt->bindParam(":status_meja", $status_meja);
        $stmt->bindParam(":kapasitas", $kapasitas);

        return $stmt->execute();
    }
    public function hapusMeja($id)
    {
        $query = "DELETE FROM meja WHERE id_meja = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // --- FUNCTION & STORED PROCEDURE ---
    public function hitungTotalPesanan($id_pesanan){
        $stmt = $this->conn->prepare("SELECT hitung_total_pesanan(:id) AS total");
        $stmt->bindParam(':id', $id_pesanan);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function cekStatusPembayaran($id_pesanan){
        $stmt = $this->conn->prepare("SELECT cek_status_pembayaran(:id) AS status");
        $stmt->bindParam(':id', $id_pesanan);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function updateStatusSelesai($id_pesanan){
        $stmt = $this->conn->prepare("CALL update_status_selesai(:id)");
        $stmt->bindParam(':id', $id_pesanan);
        return $stmt->execute();
    }
}


?>
