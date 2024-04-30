<?php
// allow all origins to access this API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once './koneksi.php';
include_once './controller/formController.php';

$database = new Database();
$db = $database->getConnection();
$form = new formController($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];

// Switch case to determine the request method and call the corresponding method
switch ($requestMethod) {
    case 'GET':
        $getPemesanan = $form->getAll();
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $form->insertData($data);
        break;

    case 'DELETE':
        // Extract the ID from the query parameters
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id !== null) {
            // Call the method to delete data
            $form->deleteData($id);
        } else {
            // Respond with error message if ID is not provided
            echo json_encode(array("message" => "ID parameter is missing"));
            http_response_code(400); // Bad request
        }
        break;
    
    default:
        echo json_encode(
            array("message" => "Method not allowed")
        );
        http_response_code(405);
        die();
        break;
}