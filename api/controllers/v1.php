<?php

header('Content-Type: application/json; charset=utf-8');

class V1 extends Controller
{
    private $_error = null;
    private $secretKey = 'secretd';

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        echo 1;
        die;
    }

    protected function authenticity()
    {
        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        } else {
            // Verify user
            $token = substr($_SERVER['HTTP_AUTHORIZATION'], strlen("Bearer "), strlen($_SERVER['HTTP_AUTHORIZATION']));
            return (new JWTAuth)->decode($token);
        }
    }

    /**
     * Log in
     */
    function auth()
    {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody);
        $this->request_method("POST");

        if (!empty($data->email) && !empty($data->password)) {
            $data = [
                'email' => $data->email,
                'password' => (new Hash)->create('md5', $data->password, HASH_PASSWORD_KEY)
            ];

            $result = $this->model->login($data);

            if (!empty($result) && is_array($result)) {
                (new Httpresponse)->set(202);

                echo json_encode([
                    "result" => true,
                    "message" => URL . 'panel',
                    "admin_info" => $result,
                    "token" => (new JWTAuth)->encode(["email" => $result["email"], "admin" => true])
                ]);
            } else {
                (new Httpresponse)->set(401);
                $this->_error = "We can not reach you!";
                echo json_encode(["result" => false, "message" => $this->_error]);
            }
        }
    }


    /**
     * Make sure that it is a correct request.
     * @param String $key
     */
    private function request_method($arg)
    {
        //header("Access-Control-Allow-Methods: " . $arg);
        if (empty($arg) || $_SERVER['REQUEST_METHOD'] !== $arg) {
            (new Httpresponse)->set(405);
            echo ('{"message":"Request method must be correct set!"}');
            exit();
        }
    }


    function subscribe()
    {
        $entityBody = file_get_contents('php://input');
        $this->request_method('POST');
        $data = json_decode($entityBody);
        if (!empty($data->email)) {
            $result = $this->model->subscribe(['email' =>  $data->email, 'firstname' =>  $data->firstname, 'lastname' => $data->lastname]);
            if (is_numeric($result)) {
                (new Httpresponse)->set(200);

                echo json_encode([
                    'result' => true,
                    'message' => 'The record has been added'
                ]);

                // mail('web3senior@gmail.com','New subscription','<mark>'.['email' =>  $data->email, 'firstname' =>  $data->firstname, 'lastname' => $data->lastname] .'</mark>');
            } else {
                (new Httpresponse)->set(400);
                echo json_encode([
                    'result' => false,
                    'message' => 'Error'
                ]);
            }
        }
    }

    private function _showError()
    {
        if (!empty($this->_error)) {
            $this->_error = "Not found any record!";
            $this->Error();
        }
    }


    function renderStatus($renderId)
    {
        $this->request_method('GET');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.shotstack.io/stage/render/' . $renderId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cache-Control: no-cache',
                'Content-Type: application/json',
                'x-api-key: C4qUqN8eoB3ZcVNimdAfeaOAmNl1yKiz2z4IT9If'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!empty($response)) {
            (new Httpresponse)->set(200);
            echo ($response);
        } else {
            $this->_error = "Not found any record!";
            $this->Error();
        }
    }

    function render()
    {
        $entityBody = file_get_contents('php://input');
        $this->request_method('POST');
        $data = $entityBody;

        if (!empty($data)) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.shotstack.io/stage/render',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Cache-Control: no-cache',
                    'Content-Type: application/json',
                    'x-api-key: C4qUqN8eoB3ZcVNimdAfeaOAmNl1yKiz2z4IT9If'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);


            $response = json_decode($response);
        
        
            if (!empty($response) && ($response->success)) {

                if ($response->message === 'Created') {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        'result' => true,
                        'renderId' => $response->response->id
                    ]);
                    exit(0);
                }
            } else {
                $this->_error = "Not found any record!";
                $this->Error();
            }
        }
    }

    function render_logo()
    {
        $entityBody = file_get_contents('php://input');
        $this->request_method('POST');
        $data = json_decode($entityBody);

        if (!empty($data)) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.shotstack.io/stage/render',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "timeline": {
                        "background": "'.$data->backgroundColor.'",
                        "fonts": [
                            {
                                "src": "https://firebasestorage.googleapis.com/v0/b/frosty-43980.appspot.com/o/fonts%2FInter-VariableFont_slnt%2Cwght.ttf?alt=media&token=d2ec8ea6-c227-4ebd-a163-1b185cc7a11e"
                            },
                            {
                                "src": "https://shotstack-assets.s3-ap-southeast-2.amazonaws.com/fonts/NunitoSans-SemiBold.ttf"
                            }
                        ],
                        "tracks": [
                            {
                                "clips": [
                                    {
                                        "asset": {
                                            "type": "html",
                                            "html": "<body>'.$data->characters.'</body>",
                                            "css": "body {border-radius:100%; font-family: Inter; font-size: '.$data->fontSize.'; color: '.$data->foregroundColor.'; text-align: center; }"
                                        },
                                        "start": 0,
                                        "length": 1,
                                        "offset": {
                                            "x": 0.042
                                        }
                                    }
                                ]
                            }
                        ]
                    },
                    "output": {
                        "format": "png",
                        "quality": "high",
                        "size": {
                            "width": 100,
                            "height": 100
                        }
                    }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Cache-Control: no-cache',
                    'Content-Type: application/json',
                    'x-api-key: 4A6A1z3HtM5H57vKt0ACV6faJHh9PHCH3ASs9Epy'
                ),
            ));

            $response =json_decode(curl_exec($curl));

            curl_close($curl);


            if($response->success) {

                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.shotstack.io/stage/render/'.$response->response->id,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  CURLOPT_HTTPHEADER => array(
                    'x-api-key: 4A6A1z3HtM5H57vKt0ACV6faJHh9PHCH3ASs9Epy'
                  ),
                ));
                
                $response =json_decode(curl_exec($curl))->response->url;

                curl_close($curl);
                
                echo json_encode(['result' => true,'data' => $response, 'ex-data' => $data]);
                
            }
        }
    }

    function queue()
    {
        $this->request_method("GET");

        if (!empty($_GET['email'])) {
            $data = $this->model->queue($_GET['email']);
            (new Httpresponse)->set(200);
            echo json_encode($data);
        } else {
            $this->_error = "Not found any record!";
            $this->Error();
        }
    }



    function template($id = false)
    {
        $this->request_method("GET");
        $data = $this->model->template($id ? $id : '');
        if (!empty($data) && is_array($data)) {
            (new Httpresponse)->set(200);
            echo json_encode($data);
        } else {
            $this->_error = "Not found any record!";
            $this->Error();
        }
    }

    function single_product($id)
    {
        $this->request_method("GET");
        if (isset($id) && !empty($id) && is_numeric($id)) {
            $data = $this->model->product_single($id);
            if (!empty($data)) {
                (new Httpresponse)->set(200);
                echo json_encode($data);
            } else {
                $this->_error = "product table is empty!";
                $this->Error();
            }
        } else {
            $this->_error = "Bad Request!";
            $this->Error();
        }
    }

    /**
     * Authorization
     * @param String $key
     */
    private function Error()
    {
        if (isset($this->_error)) {
            if (!empty($this->_error)) {
                (new Httpresponse)->set(400);
                echo ('{"message":"' . $this->_error . '"}');
            }
        } else {
            (new Httpresponse)->set(400);
            echo ('{"message":"Please contact with programmer!"}');
            exit();
        }
    }
}
