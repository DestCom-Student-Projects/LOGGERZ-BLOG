<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

spl_autoload_register(function($className){
    require $className . '.php';
});

if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    $arr = array(
        'error' => 'Only GET method is allowed',
    );
    echo json_encode($arr);
    die;
}

$body = file_get_contents("php://input");
$object = json_decode($body, true);
 
if (!is_array($object)) {
    $arr = array(
        'error' => 'Fail to decode JSON',
    );
    echo json_encode($arr);
    die;
}

if(isset($object['username']) && isset($object['password'])){
    $username = $object['username'];
    $password = $object['password'];
    
    $userManager = new UserManager;
    $user = $userManager->readByUsername($username);
    
    if($user === null){
        $arr = array(
            'status' => 'fail',
            'error' => 'User not found',
        );
        echo json_encode($arr);
        die;
    }

    if(password_verify($password, $user->getPassword())){
        $arr = array(
            'status' => 'fail',
            'error' => 'Wrong password',
            'passwordSent' => $password,
            'passwordStored' => $user->getPassword(),
        );
        echo json_encode($arr);
        die;
    }

    $arr = array(
        'status' => 'success',
        'id' => $user->getId(),
        'username' => $user->getUsername(),
        'uid' => $user->getUid(),
        'created_at' => $user->getCreatedAt(),
    );
}
else{
    $arr = array(
		'error' => 'Username or password error'
	);
}

echo json_encode($arr);

?>