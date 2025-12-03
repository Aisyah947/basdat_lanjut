<?php
class RestoranModel {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // ==== MENU ====
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

    // ==== PESANAN ====
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

    public function tambahDetailPesanan($id_pesanan, $id_menu, $jumlah)
    {
        // ambil harga satuan dari menu
        $stmtHarga = $this->conn->prepare("SELECT harga FROM menu WHERE id_menu = :id_menu");
        $stmtHarga->execute([':id_menu' => $id_menu]);
        $harga = $stmtHarga->fetchColumn();
    
        if (!$harga) {
            throw new Exception("Harga menu tidak ditemukan");
        }
    
        // insert detail pesanan
        $query = "INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, harga_satuan) 
                  VALUES (:id_pesanan, :id_menu, :jumlah, :harga_satuan)";
        
        $stmt = $this->conn->prepare($query);
    
        return $stmt->execute([
            ':id_pesanan'   => $id_pesanan,
            ':id_menu'      => $id_menu,
            ':jumlah'       => $jumlah,
            ':harga_satuan' => $harga
        ]);
    }
    


    public function getAllKaryawan()
    {
        $query = "SELECT * FROM server ORDER BY nama_server ASC";
        $stmt = $this->conn->prepare($query);
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
                ':t'     => $tanggal_pesanan,   
                ':total' => $total_harga,       
                ':st'    => $status_orderan     
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

        public function hapusDetailPesanan($id_detail)
    {
        $query = "DELETE FROM detail_pesanan WHERE id_detail_pesanan = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id_detail, PDO::PARAM_INT);

        return $stmt->execute();
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

    // ==== PELANGGAN ====
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

    public function updatePelanggan($id, $nama, $no_telepon) {
    $stmt = $this->conn->prepare("
        UPDATE pelanggan
        SET nama = :nama, no_telepon = :no_telepon
        WHERE id_pelanggan = :id
    ");

    return $stmt->execute([
        ':nama' => $nama,
        ':no_telepon' => $no_telepon,
        ':id' => $id
    ]);
    }


    // ==== MEJA ====

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
        $stmt = $this->conn->prepare($query);
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

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nomor_meja", $nomor_meja);
        $stmt->bindParam(":status_meja", $status_meja);
        $stmt->bindParam(":kapasitas", $kapasitas);

        return $stmt->execute();
    }
    public function hapusMeja($id)
    {
        $query = "DELETE FROM meja WHERE id_meja = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // ==== FUNCTION & STORED PROCEDURE ====
    public function hitungTotalPesanan($id_pesanan) {
        $stmt = $this->conn->prepare("
            SELECT COALESCE(SUM(d.jumlah * d.harga_satuan), 0) AS total
            FROM detail_pesanan d
            WHERE d.id_pesanan = :id_pesanan
        ");
        $stmt->bindParam(':id_pesanan', $id_pesanan, PDO::PARAM_INT);
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

    // ==== RESERVASI ====

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
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function tambahReservasi($id_pelanggan, $id_meja, $tanggal, $jam, $jumlah_orang, $status){
        $stmt = $this->conn->prepare("
            INSERT INTO reservasi (id_pelanggan, id_meja, tanggal_reservasi, jam_reservasi, jumlah_orang, status_reservasi)
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
        return $stmt->execute([':id' => $id]);
    }
    
      // ==== SERVER ====

    public function getAllServer() {
        $stmt = $this->conn->prepare("SELECT * FROM server ORDER BY id_server ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getServerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM server WHERE id_server = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function tambahServer($nama, $telp, $shift) {
        $sql = "INSERT INTO server (nama_server, no_telepon, shift)
                VALUES (:nama, :telp, :shift)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'nama' => $nama,
            'telp' => $telp,
            'shift' => $shift
        ]);
    }
    
    public function updateServer($id, $nama, $telp, $shift) {
        $sql = "UPDATE server SET nama_server = :nama, no_telepon = :telp, shift = :shift
                WHERE id_server = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'nama' => $nama,
            'telp' => $telp,
            'shift' => $shift,
            'id' => $id
        ]);
    }
    
    public function hapusServer($id) {
        $stmt = $this->conn->prepare("DELETE FROM server WHERE id_server = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ===== LAPORAN SHIFT ==== 
    public function getAllLaporanShift() {
        $stmt = $this->conn->prepare("
            SELECT l.*, s.nama_server 
            FROM laporan_shift l
            JOIN server s ON l.id_server = s.id_server
            ORDER BY l.tanggal DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah laporan shift
    public function tambahLaporanShift($id_server, $tanggal, $mulai, $selesai, $total_penjualan, $total_pesanan, $shift) {
        $stmt = $this->conn->prepare("
            INSERT INTO laporan_shift 
            (id_server, tanggal, waktu_mulai, waktu_selesai, total_penjualan, total_pesanan, shift)
            VALUES (:server, :tgl, :mulai, :selesai, :jual, :pesan, :sh)
        ");
        return $stmt->execute([
            ':server' => $id_server,
            ':tgl'    => $tanggal,
            ':mulai'  => $mulai,
            ':selesai'=> $selesai,
            ':jual'   => $total_penjualan,
            ':pesan'  => $total_pesanan,
            ':sh'     => $shift
        ]);
    }

    // Ambil laporan shift berdasarkan ID
    public function getLaporanShiftById($id_laporan) {
        $stmt = $this->conn->prepare("SELECT * FROM laporan_shift WHERE id_laporan = :id");
        $stmt->execute([':id' => $id_laporan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Edit laporan shift
    public function editLaporanShift($id_laporan, $id_server, $tanggal, $mulai, $selesai, $total_penjualan, $total_pesanan, $shift) {
        $stmt = $this->conn->prepare("
            UPDATE laporan_shift SET
                id_server = :server,
                tanggal = :tgl,
                waktu_mulai = :mulai,
                waktu_selesai = :selesai,
                total_penjualan = :jual,
                total_pesanan = :pesan,
                shift = :sh
            WHERE id_laporan = :id
        ");
        return $stmt->execute([
            ':id'     => $id_laporan,
            ':server' => $id_server,
            ':tgl'    => $tanggal,
            ':mulai'  => $mulai,
            ':selesai'=> $selesai,
            ':jual'   => $total_penjualan,
            ':pesan'  => $total_pesanan,
            ':sh'     => $shift
        ]);
    }

    // Hapus laporan shift
    public function hapusLaporanShift($id_laporan) {
        $stmt = $this->conn->prepare("DELETE FROM laporan_shift WHERE id_laporan = :id");
        return $stmt->execute([':id' => $id_laporan]);
    }

    //==== LAPORAN PENJUALAN ====
    public function getPenjualanPerMenu(){
        return $this->conn->query("SELECT * FROM mv_penjualan_per_menu")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPenjualanPerShift(){
        return $this->conn->query("SELECT * FROM v_penjualan_per_shift")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPenjualanPerServer(){
        return $this->conn->query("SELECT * FROM v_penjualan_per_server")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMenuTerlaris(){
        return $this->conn->query("SELECT * FROM v_menu_terlaris")->fetch(PDO::FETCH_ASSOC);
    }
}



    
?>