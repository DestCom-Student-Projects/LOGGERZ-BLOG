<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

spl_autoload_register(function($className){
    require $className . '.php';
});

if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    $arr = array(
        'error' => 'Only POST method is allowed',
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
    $password = base64_decode($password);
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    $password =  password_hash($password, PASSWORD_DEFAULT);
    
    $userManager = new UserManager;
    $user = $userManager->readByUsername($username);

    if($user !== null){
        $arr = array(
            'status' => 'fail',
            'error' => 'User already exists',
        );
        echo json_encode($arr);
        die;
    }

    $user = new User();
    $user->setUsername($username);
    $user->setPassword($password);
    $uid = uniqid();
    $user->setUid($uid);

    $userManager = new UserManager();
    $create = $userManager->create($user);

    $arr = array(
		'token' => $uid,
        'password' => $password,
	);
}
else{
    $arr = array(
		'error' => 'Username or password error'
	);
}

echo json_encode($arr);

?>