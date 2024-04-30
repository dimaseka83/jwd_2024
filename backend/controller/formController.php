<?php 

class formController {
    // private is only accessible within the class
    private $con = null;

    // constructor is a special type of method which is called when an object is created
    public function __construct($db)
    {
        $this->con = $db;
    }

    // function to get all data from tb_pemesanan
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM tb_pemesanan";
            $result = $this->con->query($sql);
            // return response json
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
            } else {
                echo json_encode(
                    array()
                );
            }
            
        } catch (\Throwable $e) {
            echo json_encode(
                array("message" => "Failed to get data.")
            );
        }
    }

    // function to insert or Update data by id from tb_pemesanan
    public function insertData($post) {
        try {
            $post = (array) $post;

            $id = isset($post['id']) ? $post['id'] : null;
            $nama = $post['nama'];
            $email = $post['email'];
            $phone = $post['phone'];
            $jumlah_org = $post['jumlah_org'];
            $tgl_berangkat = $post['tgl_berangkat'];
            $tgl_pulang = $post['tgl_pulang'];
            $paket_inap = $post['paket_inap'];
            $paket_transport = $post['paket_transport'];
            $paket_makan = $post['paket_makan'];
            if (isset($post['id'])) {
                $sql = "UPDATE tb_pemesanan SET nama='$nama', email='$email', phone='$phone', jumlah_org='$jumlah_org', tgl_berangkat='$tgl_berangkat', tgl_pulang='$tgl_pulang', paket_inap='$paket_inap', paket_transport='$paket_transport', paket_makan='$paket_makan' WHERE id='$id'";
            } else {
                $sql = "INSERT INTO tb_pemesanan (nama, email, phone, jumlah_org, tgl_berangkat, tgl_pulang, paket_inap, paket_transport, paket_makan) VALUES ('$nama', '$email', '$phone', '$jumlah_org', '$tgl_berangkat', '$tgl_pulang', '$paket_inap', '$paket_transport', '$paket_makan')";
            }
            $result = $this->con->query($sql);
            if ($result) {
                echo json_encode(
                    array("message" => "Data berhasil ditambahkan.")
                );
            } else {
                echo json_encode(
                    array("message" => "Data gagal ditambahkan.")
                );
            }
        } catch (\Throwable $e) {
            echo json_encode(
                array("message" => $e->getMessage())
            );
        }
    }

    // function to delete data by id from tb_pemesanan
    public function deleteData($id) {
        try {
            $sql = "DELETE FROM tb_pemesanan WHERE id='$id'";
            $result = $this->con->query($sql);
            if ($result) {
                echo json_encode(
                    array("message" => "Data berhasil dihapus.")
                );
            } else {
                echo json_encode(
                    array("message" => "Data gagal dihapus.")
                );
            }
        } catch (\Throwable $e) {
            echo json_encode(
                array("message" => $e->getMessage())
            );
        }
    }
}
?>