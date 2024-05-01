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
// The second statement includes a file named "formController.php", suggesting that it holds code related to managing data and interactions with a "wisata" entity, possibly part of a broader MVC (Model-View-Controller) architecture for handling requests and data manipulation in a web application.
include_once './koneksi.php';
include_once './controller/formController.php';

// These lines of PHP code initialize a new instance of the Database class, likely responsible for managing the database connection.
$database = new Database();
$db = $database->getConnection();
$form = new formController($db);

// These lines of PHP code retrieve the HTTP request method from the $_SERVER superglobal and store it in the $requestMethod variable.
$requestMethod = $_SERVER["REQUEST_METHOD"];

// The provided PHP switch statement handles different HTTP request methods (GET, POST, DELETE) for a specific endpoint.
//  For GET requests, it calls a method to retrieve all data.
//   In the case of a POST request, it decodes the JSON data from the request body and inserts it into the database. 
//   For DELETE requests, it extracts the ID parameter from the query string and deletes the corresponding data. 
//   If the ID parameter is missing in the DELETE request, it responds with a JSON error message and sets the HTTP response code to 400 (Bad request). 
//   Additionally, it includes a default case to handle any other request method by responding with a message indicating that the method is not allowed and setting the HTTP response code to 405 (Method Not Allowed).
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