<?php
class wisataController
{
    private $con = null;

    public function __construct($db)
    {
        $this->con = $db;
    }

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

    public function updateData($put_json)
    {
        try {
            // Convert $put_json to an array (if it's not already an array)
            $data = (array) $put_json;

            // Check if the 'id' key exists in the array
            if (!isset($data['id'])) {
                throw new Exception("ID not provided.");
            }

            // Get the existing data for this ID
            $existingData = $this->getDataById($data['id']);

            if (!$existingData) {
                throw new Exception("Data not found for ID: " . $data['id']);
            }

            // Prepare updated data
            $nama = isset($data['nama']) ? $data['nama'] : $existingData['nama'];
            $link = isset($data['link']) ? $data['link'] : $existingData['link'];
            $img = isset($data['img']) ? $data['img'] : null;

            // If a new image is provided, update the image
            $imgPath = null;
            if ($img) {
                $imgPath = $this->insertImg($img);
                if (!$imgPath) {
                    throw new Exception("Failed to upload new image.");
                }

                // Delete the old image file if it exists
                if ($existingData['img']) {
                    $oldImagePath = "assets/img/" . $existingData['img'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            } else {
                // If no new image provided, keep the existing image path
                $imgPath = $existingData['img'];
            }

            // Update data in the database
            $sql = "UPDATE tb_wisata SET nama='$nama', link='$link', img='$imgPath' WHERE id='{$data['id']}'";
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
