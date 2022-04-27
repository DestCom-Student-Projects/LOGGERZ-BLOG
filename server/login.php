<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

$res = openssl_pkey_new();
openssl_pkey_export_to_file($res, './rsaPrivateKey.key');
$privateKey = file_get_contents('./rsaPrivateKey.key');
$res = openssl_pkey_get_private($privateKey);

/* echo openssl_pkey_get_details($res)['key'];
die; */

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
    $password = base64_decode($password);
    
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
        $key = openssl_pkey_get_details($res)['key'];
        $payload = [
            'iss' => 'LOGGERZ',
            'aud' => 'USERZ',
            'iat' => time(),
            'exp' => time() + 600,
            'data' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'uid' => $user->getUid(),
                'created_at' => $user->getCreatedAt(),
            ],
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        $arr = array(
            'status' => 'success',
            'token' => $jwt,
        );
        echo json_encode($arr);
        die;
    }
    else{
        $arr = array(
            'status' => 'fail',
            'error' => 'Wrong password',  
            'password' => $password,
            'hash' => $user->getPassword(),
        );
        echo json_encode($arr);
        die;
    }
}
else{
    $arr = array(
		'error' => 'Username or password error'
	);
}

echo json_encode($arr);

?>
?>