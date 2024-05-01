<?php
// The `wisataController` class is responsible for controlling interactions related to the "tb_wisata" table in the database.
//  It has a private property `$con` to store the database connection. 
//  The constructor method accepts a database connection `$db` as a parameter and initializes the `$con` property with this connection. 
// This design enables the class to access the database connection throughout its methods for executing queries and performing database operations related to the "tb_wisata" table.
class wisataController
{
    private $con = null;

    public function __construct($db)
    {
        $this->con = $db;
    }

// The `getAll` method retrieves all data from the "tb_wisata" table. 
// It executes a SQL query to select all records from the table.
//  If there are rows returned from the query, it fetches each row and stores it in an array.
//   Finally, it encodes the array into JSON format and echoes it as the response. 
// If there are no rows returned, it echoes an empty JSON array. 
// In case of any errors during the execution of the method, it catches the exception, encodes an error message into a JSON object, and echoes it as the response.
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM tb_wisata";
            $result = $this->con->query($sql);
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

    // The insertData method within the wisataController class is responsible for inserting new data into the "tb_wisata" table in the database.
    //  It takes a JSON object $post_json as input, which is converted to an associative array. 
    //  The method extracts the necessary data such as the name, link, and image path from the array. 
    // It then constructs an SQL query to insert this data into the database.
    public function insertData($post_json)
    {
        try {
            // Convert $post_json to an array (if it's not already an array)
            $data = (array) $post_json;

            // Check if the 'id' key exists in the array
            $nama = $data['nama'];
            $link = $data['link'];
            $img = $data['img'];

            // img
            $imgPath = $this->insertImg($img);

            // Check if the 'id' key exists in the array
            $sql = "INSERT INTO tb_wisata (nama, link, img) VALUES ('$nama', '$link', '$imgPath')";


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

    // The `updateData` method within the `wisataController` class handles the updating of existing data in the "tb_wisata" table of the database. 
    // It takes a JSON object `$post_json` as input, converts it to an associative array, and extracts the necessary data such as the name, link, and ID from the array. Depending on whether the image has been edited (`imgEdited` is set to 'edited'), it may delete the existing image associated with the record from the server. It then constructs an SQL query to update the corresponding record with the new data. Upon successful execution of the query, it returns a JSON response indicating success. If an error occurs during the process, it returns a JSON response containing the error message. 
    // This method ensures proper handling of database update operations and provides feedback to the user about the outcome of the operation.
    public function updateData($post_json)
    {
        try {
            // Convert $put_json to an array (if it's not already an array)
            // Convert $post_json to an array (if it's not already an array)
            $data = (array) $post_json;

            // Check if the 'id' key exists in the array
            $nama = $data['nama'];
            $link = $data['link'];

            // // Check if the 'id' key exists in the array
            $id = $data['id'];

            if($data['imgEdited'] == 'edited') {
                // delete image
                $sqlGetImg = "SELECT img FROM tb_wisata WHERE id='$id'";
                $resultGetImg = $this->con->query($sqlGetImg);
                $img = $resultGetImg->fetch_assoc();
                $imgPath = "assets/img/" . $img['img'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }

                // update data
                $img = $data['img'];
                $imgPath = $this->insertImg($img);

                $sql = "UPDATE tb_wisata SET nama='$nama', link='$link', img='$imgPath' WHERE id='$id'";
            } else {
                $sql = "UPDATE tb_wisata SET nama='$nama', link='$link' WHERE id='$id'";
            }

            $result = $this->con->query($sql);

            if ($result) {
                echo json_encode(
                    array("message" => "Data berhasil diupdate.")
                );
            } else {
                echo json_encode(
                    array("message" => "Data gagal diupdate.")
                );
            }
            
        } catch (\Throwable $e) {
            echo json_encode(
                array("message" => $e->getMessage())
            );
        }
    }

    // The `deleteData` method within the `wisataController` class handles the deletion of a record from the "tb_wisata" table in the database. 
    // It takes the ID of the record to be deleted as input. 
    // The method constructs an SQL query to delete the record with the specified ID from the table. 
    // Additionally, it retrieves the image file path associated with the record and deletes the corresponding image file from the server if it exists. After executing the deletion query and ensuring the deletion of the image file, it returns a JSON response indicating the success or failure of the deletion operation, along with an appropriate message. In case of any exceptions or errors during the process, it returns a JSON response containing the error message. This method ensures proper handling of record deletion and provides feedback to the user about the outcome of the operation.
    public function deleteData($id)
    {
        try {
            $sql = "DELETE FROM tb_wisata WHERE id='$id'";
            // delete image
            $sqlGetImg = "SELECT img FROM tb_wisata WHERE id='$id'";
            $resultGetImg = $this->con->query($sqlGetImg);
            $img = $resultGetImg->fetch_assoc();
            $imgPath = "assets/img/" . $img['img'];
            if (file_exists($imgPath)) {
                unlink($imgPath);
            }
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

    // The `insertImg` method within the `wisataController` class handles the uploading of image files to the server. 
    // It takes the uploaded image file as input and performs several validation checks before moving the file to the specified directory.
    //  The method first determines the target directory and generates a unique filename based on the current date, time, and a unique identifier. It then performs validation checks on the uploaded file, including checking its type, size, and whether it is a valid image file. If the file passes all the checks, it is moved to the target directory, and the method returns the filename of the uploaded image. Otherwise, it returns false, indicating that the upload process failed. 
    // This method ensures that only valid image files are uploaded to the server and provides feedback on the success or failure of the upload operation.
    public function insertImg($img)
    {
        // move the uploaded image to the folder
        $target_dir = "assets/img/";
        $imageFileType = strtolower(pathinfo($img["name"], PATHINFO_EXTENSION));

        // Generate unique filename using current date and time
        $newFileName = date('YmdHis') . '_' . uniqid() . '.' . $imageFileType;

        $target_file = $target_dir . $newFileName;
        $uploadOk = 1;

        // Check if the directory exists, if not, create it
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Adjust permission as necessary
        }

        // Your existing validation checks...

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($img["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        // Check file size
        if ($img["size"] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($img["tmp_name"], $target_file)) {
                // return the path of the uploaded image
                return $newFileName;
            } else {
                return false;
            }
        }
    }
}
