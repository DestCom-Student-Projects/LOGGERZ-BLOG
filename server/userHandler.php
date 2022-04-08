<?php 

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");

spl_autoload_register(function($className){
    require $className . '.php';
});

/* echo json_encode(array(
    'status' => 'ok',
    'message' => 'Hello World!',
    'request' => $_SERVER['REQUEST_METHOD'],
    'data' => $_POST,
    'action' => $_GET['action']
));
die; */

if(strtoupper($_SERVER['REQUEST_METHOD']) === 'GET'){
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }
    else{
        $action = 'default';
    }

    if(isset($_GET['token'])){
        $token = $_GET['token'];
    }
    else{
        echo json_encode(array(
            'status' => 'error',
            'message' => 'No token given'
        ));
        die;
    }

    if($action === 'getUserInfos'){
        //Select all user infos from token
        $userManager = new userManager();
        $user = $userManager->readByToken($token);

        if(empty($user)){
            echo json_encode(array(
                'status' => 'error',
                'message' => 'No user found'
            ));
            die;
        }

        echo json_encode(array(
            'status' => 'ok',
            'message' => 'User infos found',
            'username' => $user->getUsername(),
            'created_at' => $user->getCreatedAt()
        ));
    }
}

?>