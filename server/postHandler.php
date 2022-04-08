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

    if($action === 'getAllPosts'){
        $postManager = new postManager();
        $posts = $postManager->readAll();

        if(empty($posts)){
            echo json_encode(array(
                'status' => 'nopost',
                'message' => 'No posts found'
            ));
            die;
        }

        //Create a array of post 
        $postArray = array();
        foreach($posts as $post){
            //Get users info from uid token
            $userManager = new userManager();
            $user = $userManager->readByToken($post->getAuthorUid());

            $postArray[] = array(
                'id' => $post->getId(),
                'author' => $user->getUsername(),
                'author_uid' => $post->getAuthorUid(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()
            );
        }
        
        echo json_encode(array_reverse($postArray));
        die();
    }
}


if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
    $body = file_get_contents("php://input");
    $object = json_decode($body, true);
 
    if (!is_array($object)) {
        $arr = array(
            'error' => 'Fail to decode JSON',
        );
        echo json_encode($arr);
        die;
    }


    if (!isset($object['title']) && !isset($object['content']) && !isset($object['author_uid'])) {
        $arr = array(
            'error' => 'Missing informations',
        );
        echo json_encode($arr);
        die;
    }

    $title = $object['title'];
    $content = $object['content'];
    $author_uid = $object['author_uid'];

    $post = new Post();
    $post->setTitle($title);
    $post->setContent($content);
    $post->setAuthorUid($author_uid);

    $postManager = new PostManager();
    $create = $postManager->create($post);

    $arr = array(
        'status' => 'success',
        'id' => $create,
    );

    echo json_encode($arr);
    die;
}



?>