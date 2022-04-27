<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function($className){
    require $className . '.php';
});

use Firebase\JWT\JWT;

$res = openssl_pkey_new();
openssl_pkey_export_to_file($res, './rsaPrivateKey.key');
$privateKey = file_get_contents('./rsaPrivateKey.key');
$res = openssl_pkey_get_private($privateKey);


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
    $password = password_hash($password, PASSWORD_DEFAULT);
    
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

    $key = openssl_pkey_get_details($res)['key'];
    $payload = [
        'iss' => 'LOGGERZ',
        'aud' => 'USERZ',
        'iat' => time(),
        'exp' => time() + 600,
        'data' => [
            'id' => $create,
            'username' => $username,
            'uid' => $uid,
            'created_at' => $password,
        ],
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');
    $arr = array(
        'status' => 'success',
        'token' => $jwt,
        'psswd' => $object['password'],
        "encrypt" => $password,
    );
}
else{
    $arr = array(
		'error' => 'Username or password error'
	);
}

echo json_encode($arr);

?>