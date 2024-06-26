<?php 

class formController {
    // private is only accessible within the class
    private $con = null;

    // constructor is a special type of method which is called when an object is created
    public function __construct($db)
    {
        $this->con = $db;
    }

    // The `getAll` method within the PHP class retrieves all records from the `tb_pemesanan` table in descending order by ID. 
    // It executes a SQL query to fetch the data and formats the result into a JSON response. 
    // If there are records found, it encodes them into JSON format and echoes the result. 
    // If no records are found, it echoes an empty JSON array.
    //  Additionally, it includes error handling to catch any exceptions that may occur during the database query execution and responds with a JSON error message indicating the failure to retrieve data.
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM tb_pemesanan ORDER BY id DESC";
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

    // The `insertData` method of the PHP class handles the insertion of new data into the `tb_pemesanan` table based on the provided POST data. 
    // It first casts the input data to an associative array and extracts relevant fields such as name, email, phone number, number of people, travel dates, and package details. 
    // Depending on whether an ID is provided in the input data, it constructs either an SQL UPDATE or INSERT query accordingly. Upon execution of the query, it echoes a JSON response indicating the success or failure of the operation along with appropriate status codes. If successful, it returns a message confirming the data addition and includes the inserted data in the response.
    //  If an exception occurs during the database operation, it returns an error message along with a status code of 500.
    public function insertData($post) {
        try {
            $post = (array) $post;

            $id = isset($post['id']) ? $post['id'] : null;
            $nama = $post['nama'];
            $identitas = $post['identitas'];
            $kelamin = $post['kelamin'];
            $kamar = $post['kamar'];
            $harga = $post['harga'];
            $tglpesan = $post['tglpesan'];
            $jmlHari = $post['jmlHari'];
            $breakfast = isset($post['breakfast']) ? 1 : 0;
            if (isset($post['id'])) {
                $sql = "UPDATE tb_pemesanan SET nama='$nama', identitas='$identitas', kelamin='$kelamin', kamar='$kamar', harga='$harga', tglpesan='$tglpesan', jmlHari='$jmlHari', breakfast='$breakfast' WHERE id='$id'";
            } else {
                $sql = "INSERT INTO tb_pemesanan (nama, identitas, kelamin, kamar, harga, tglpesan, jmlHari, breakfast) VALUES ('$nama', '$identitas', '$kelamin', '$kamar', '$harga', '$tglpesan', '$jmlHari', '$breakfast')";
            }
            $result = $this->con->query($sql);
            if ($result) {
                echo json_encode(
                    // result data saved to json and return 200
                    array("message" => "Data berhasil ditambahkan.", "data" => $post, "status" => 200)
                );
            }
        } catch (\Throwable $e) {
            // status 500 if failed to save data
            echo json_encode(
                array("message" => $e->getMessage(), "status" => 500)
            );
        }
    }

    // The `deleteData` method within the PHP class handles the deletion of a specific record from the `tb_pemesanan` table based on the provided ID. 
    // It constructs a SQL DELETE query to remove the record with the specified ID. 
    // Upon execution of the query, it checks whether the deletion was successful. If successful, it echoes a JSON response indicating the successful deletion of the data. 
    // If the deletion fails, it returns a message indicating the failure.
    //  In case of any exceptions during the database operation, it returns an error message indicating the cause of the failure.
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