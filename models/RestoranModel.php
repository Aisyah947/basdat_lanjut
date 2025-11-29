<?php
class RestoranModel {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // =========================
    //        MENU
    // =========================

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

        // Total menu
        $stmtTotal = $this->conn->prepare("SELECT COUNT(*) FROM menu");
        $stmtTotal->execute();
        $stats['total_menu'] = $stmtTotal->fetchColumn();

        // Statistik kategori
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

        return array_merge($stats, $res ?: []);
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
            ':status'    => $status ? 'true' : 'false', 
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

    public function getAllKategori(){
        $stmt = $this->conn->prepare("SELECT * FROM kategori_menu ORDER BY nama_kategori ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMenuByKategori($id_kategori){
        $stmt = $this->conn->prepare("
            SELECT m.*, km.nama_kategori 
            FROM menu m
            JOIN kategori_menu km ON m.id_kategori = km.id_kategori
            WHERE m.id_kategori = :id_kategori
            ORDER BY m.nama_menu ASC
        ");
        $stmt->execute([':id_kategori' => $id_kategori]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================
    //        PESANAN
    // =========================

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

    public function getDetailPesanan($id) {
        $stmt = $this->conn->prepare("
            SELECT dp.id_menu, dp.jumlah, m.nama_menu, m.harga
            FROM detail_pesanan dp
            JOIN menu m ON dp.id_menu = m.id_menu
            WHERE dp.id_pesanan = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahPesanan($id_pelanggan, $id_meja, $id_server, $tanggal_pesanan, $total_harga, $status_orderan) {
        try {
            $sql = "INSERT INTO pesanan 
                    (id_pelanggan, id_meja, id_server, tanggal_pesanan, total_harga, status_orderan)
                    VALUES (:p, :m, :s, :t, :total, :st)";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':p'     => $id_pelanggan,
                ':m'     => $id_meja,
                ':s'     => $id_server,
                ':t'     => $tanggal_pesanan,   
                ':total' => $total_harga,       
                ':st'    => $status_orderan     
            ]);

        } catch (PDOException $e) {
            echo "Error PostgreSQL: " . $e->getMessage();
            return false;
        }
    }

    public function getPesananById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePesanan($id, $id_pelanggan, $id_meja, $id_server, $status) {
        $stmt = $this->conn->prepare("
            UPDATE pesanan SET
                id_pelanggan = :p,
                id_meja = :m,
                id_server = :s,
                status_orderan = :status
            WHERE id_pesanan = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':p' => $id_pelanggan,
            ':m' => $id_meja,
            ':s' => $id_server,
            ':status' => $status
        ]);
    }

    public function hapusDetailPesanan($id_detail){
        $stmt = $this->conn->prepare("DELETE FROM detail_pesanan WHERE id_detail_pesanan = :id");
        return $stmt->execute([':id' => $id_detail]);
    }

    public function hapusPesanan($id) {

        $this->conn->prepare("DELETE FROM detail_pesanan WHERE id_pesanan = :id")
            ->execute([':id' => $id]);

        $this->conn->prepare("DELETE FROM pembayaran WHERE id_pesanan = :id")
            ->execute([':id' => $id]);

        $stmt3 = $this->conn->prepare("DELETE FROM pesanan WHERE id_pesanan = :id");
        return $stmt3->execute([':id' => $id]);
    }


    // =========================
    //       PELANGGAN
    // =========================

    public function getAllPelanggan(){
        $stmt = $this->conn->prepare("SELECT * FROM pelanggan ORDER BY id_pelanggan ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPelangganById($id){
        $stmt = $this->conn->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahPelanggan($nama, $no_telp){
        $stmt = $this->conn->prepare("
            INSERT INTO pelanggan (nama, no_telepon)
            VALUES (:nama, :no_telp)
        ");
        return $stmt->execute([
            ':nama' => $nama,
            ':no_telp' => $no_telp
        ]);
    }

    public function updatePelanggan($id, $nama, $no_telepon){
        $stmt = $this->conn->prepare("
            UPDATE pelanggan 
            SET nama = :nama,
                no_telepon = :no_telepon
            WHERE id_pelanggan = :id
        ");

        return $stmt->execute([
            ':id'          => $id,
            ':nama'        => $nama,
            ':no_telepon'  => $no_telepon
        ]);
    }

    public function hapusPelanggan($id){
        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan = :id");
        return $stmt->execute([':id' => $id]);
    }



    // =========================
    //          MEJA
    // =========================

    public function getAllMeja(){
        $stmt = $this->conn->prepare("SELECT * FROM meja ORDER BY id_meja ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMejaById($id){
        $stmt = $this->conn->prepare("SELECT * FROM meja WHERE id_meja = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahMeja($nomor_meja, $status_meja, $kapasitas){
        $stmt = $this->conn->prepare("
            INSERT INTO meja (nomor_meja, status_meja, kapasitas) 
            VALUES (:nomor_meja, :status_meja, :kapasitas)
        ");

        return $stmt->execute([
            ':nomor_meja' => $nomor_meja,
            ':status_meja' => $status_meja,
            ':kapasitas' => $kapasitas
        ]);
    }

    public function updateMeja($id, $nomor_meja, $status_meja, $kapasitas){
        $stmt = $this->conn->prepare("
            UPDATE meja 
            SET nomor_meja = :nomor_meja,
                status_meja = :status_meja,
                kapasitas = :kapasitas
            WHERE id_meja = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':nomor_meja' => $nomor_meja,
            ':status_meja' => $status_meja,
            ':kapasitas' => $kapasitas
        ]);
    }

    public function hapusMeja($id){
        $stmt = $this->conn->prepare("DELETE FROM meja WHERE id_meja = :id");
        return $stmt->execute([':id' => $id]);
    }


    // =========================
    //     FUNCTION/PROCEDURE
    // =========================

    public function hitungTotalPesanan($id_pesanan){
        $stmt = $this->conn->prepare("SELECT hitung_total_pesanan(:id) AS total");
        $stmt->execute([':id'=>$id_pesanan]);
        return $stmt->fetchColumn();
    }

    public function cekStatusPembayaran($id_pesanan){
        $stmt = $this->conn->prepare("SELECT cek_status_pembayaran(:id) AS status");
        $stmt->execute([':id'=>$id_pesanan]);
        return $stmt->fetchColumn();
    }

    public function updateStatusSelesai($id_pesanan){
        $stmt = $this->conn->prepare("CALL update_status_selesai(:id)");
        return $stmt->execute([':id' => $id_pesanan]);
    }


    // =========================
    //       RESERVASI
    // =========================

    public function getAllReservasi(){
        $stmt = $this->conn->prepare("
            SELECT r.*, p.nama AS nama_pelanggan, m.nomor_meja
            FROM reservasi r
            JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
            JOIN meja m ON r.id_meja = m.id_meja
            ORDER BY r.id_reservasi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservasiById($id){
        $stmt = $this->conn->prepare("SELECT * FROM reservasi WHERE id_reservasi = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahReservasi($id_pelanggan, $id_meja, $tanggal, $jam, $jumlah_orang, $status){
        $stmt = $this->conn->prepare("
            INSERT INTO reservasi 
            (id_pelanggan, id_meja, tanggal_reservasi, jam_reservasi, jumlah_orang, status_reservasi)
            VALUES (:p, :m, :tgl, :jam, :jml, :st)
        ");

        return $stmt->execute([
            ':p'   => $id_pelanggan,
            ':m'   => $id_meja,
            ':tgl' => $tanggal,
            ':jam' => $jam,
            ':jml' => $jumlah_orang,
            ':st'  => $status
        ]);
    }

    public function updateReservasi($id, $id_pelanggan, $id_meja, $tanggal, $jam, $jumlah_orang, $status){
        $stmt = $this->conn->prepare("
            UPDATE reservasi
            SET id_pelanggan = :p,
                id_meja = :m,
                tanggal_reservasi = :tgl,
                jam_reservasi = :jam,
                jumlah_orang = :jml,
                status_reservasi = :st
            WHERE id_reservasi = :id
        ");

        return $stmt->execute([
            ':id'  => $id,
            ':p'   => $id_pelanggan,
            ':m'   => $id_meja,
            ':tgl' => $tanggal,
            ':jam' => $jam,
            ':jml' => $jumlah_orang,
            ':st'  => $status
        ]);
    }

    public function hapusReservasi($id){
        $stmt = $this->conn->prepare("DELETE FROM reservasi WHERE id_reservasi = :id");
        return $stmt->execute([':id'=>$id]);
    }


    // =========================
    //         SERVER
    // =========================

    public function getAllServer(){
        $stmt = $this->conn->prepare("SELECT * FROM server ORDER BY id_server ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServerById($id){
        $stmt = $this->conn->prepare("SELECT * FROM server WHERE id_server = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahServer($nama, $telp, $shift){
        $stmt = $this->conn->prepare("
            INSERT INTO server (nama_server, no_telepon, shift)
            VALUES (:nama, :telp, :shift)
        ");

        return $stmt->execute([
            ':nama'  => $nama,
            ':telp'  => $telp,
            ':shift' => $shift
        ]);
    }

    public function updateServer($id, $nama, $telp, $shift){
        $stmt = $this->conn->prepare("
            UPDATE server 
            SET nama_server = :nama,
                no_telepon = :telp,
                shift = :shift
            WHERE id_server = :id
        ");

        return $stmt->execute([
            ':id'    => $id,
            ':nama'  => $nama,
            ':telp'  => $telp,
            ':shift' => $shift
        ]);
    }

    public function hapusServer($id){
        $stmt = $this->conn->prepare("DELETE FROM server WHERE id_server = :id");
        return $stmt->execute([':id'=>$id]);
    }

}
?>
