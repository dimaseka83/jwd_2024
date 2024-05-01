<?php
// allow all origins to access this API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once './koneksi.php';
include_once './controller/wisataController.php';

$database = new Database();
$db = $database->getConnection();
$wisata = new wisataController($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];

// Switch case to determine the request method and call the corresponding method
switch ($requestMethod) {
    case 'GET':
        $getWisata = $wisata->getAll();
        break;

    case 'POST':
        $nama = $_POST['nama'];
        $link = $_POST['link'];
        $img = $_FILES['img'];
        $imgEdited = $_FILES['img']['name'] ? 'edited' : 'not edited';

        // create an array to store all data
        $data = array(
            'nama' => $nama,
            'link' => $link,
            'img' => $img,
            'imgEdited' => $imgEdited
        );

        // // insert or update data
        if (isset($_GET['id'])) {
            $data['id'] = $_GET['id'];
            $updateWisata = $wisata->updateData($data);
        } else {
            $insertWisata = $wisata->insertData($data);
        }
        break;

        case 'DELETE':
        // get id from query string
        $id = isset($_GET['id']) ? $_GET['id'] : die();

        // delete data
        $deleteWisata = $wisata->deleteData($id);
        break;

    default:
        echo json_encode(
            array("message" => "Method not allowed")
        );
        http_response_code(405);
        die();
        break;
}
