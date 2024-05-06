<?php
// These PHP headers are used to manage Cross-Origin Resource Sharing (CORS) in web applications. 
// They allow requests from any origin, specify the content type as JSON with UTF-8 encoding, restrict allowed HTTP methods to GET, and define which headers can be used in requests. 
// These measures help control and secure access to server resources, ensuring that only authorized requests are permitted and that the response format is appropriately specified.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// These PHP include_once statements are used to include external files into the current script. 
// The first statement includes a file named "koneksi.php", which likely contains code related to establishing a database connection. 
// The second statement includes a file named "kamarController.php", suggesting that it holds code related to managing data and interactions with a "wisata" entity, possibly part of a broader MVC (Model-View-Controller) architecture for handling requests and data manipulation in a web application.
include_once './koneksi.php';
include_once './controller/kamarController.php';

// These lines of PHP code initialize a new instance of the Database class, likely responsible for managing the database connection.
// Then, it establishes a connection to the database using the getConnection method. 
// After that, it creates a new instance of the kamarController class, passing the database connection as a parameter. 
// This suggests that the kamarController class is responsible for handling operations related to the "wisata" entity in the application, such as retrieving, updating, or deleting data from the corresponding database table.
$database = new Database();
$db = $database->getConnection();
$wisata = new kamarController($db);

// These lines of PHP code retrieve the HTTP request method from the $_SERVER superglobal and store it in the $requestMethod variable.
$requestMethod = $_SERVER["REQUEST_METHOD"];

// This PHP code snippet represents a switch statement that handles different HTTP request methods (GET, POST, DELETE) received by the server. 
// For a GET request, it retrieves all "wisata" data using the getAll method from the kamarController class. 
// For a POST request, it extracts data from the request body ($_POST) including 'nama', 'link', and 'img'. 
// It then determines whether to insert or update data based on the presence of an 'id' parameter in the URL query string. 
// If the request is a DELETE method, it extracts the 'id' parameter from the query string and invokes the deleteData method from the kamarController class to remove the corresponding data entry. 
// If none of these request methods match, it returns a message indicating that the method is not allowed with an HTTP response code of 405.
switch ($requestMethod) {
    case 'GET':
        $getWisata = $wisata->getAll();
        break;

    case 'POST':
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $link = $_POST['link'];
        $img = $_FILES['img'];
        $imgEdited = $_FILES['img']['name'] ? 'edited' : 'not edited';

        // create an array to store all data
        $data = array(
            'nama' => $nama,
            'harga' => $harga,
            'img' => $img,
            'link' => $link,
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
