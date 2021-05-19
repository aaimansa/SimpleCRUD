<?php

include 'functions.php';
$pdo = pdo_connect();

session_start();

function csrf_token() {
    return bin2hex(rand(100000, 999999));
}

function create_csrf_token() {
    $token = csrf_token();
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
    return $token;
}

function csrf_token_tag() {
    $token = create_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        die ('Contact doesn\'t exist!');
    }
} else {
    die ('No ID specified!');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?=style_script()?>
    <title>Document</title>
</head>
<body>
<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Update contact # <?=$contact['id']?></h5>                    
                
                
                <form action="response2.php?id=<?=$contact['id']?>" method="post">
                    <input class="form-control form-control-sm" placeholder="Type name" type="text" name="name" value="<?=$contact['name']?>" id="name" required><br>
                    <input class="form-control form-control-sm" placeholder="Email" type="text" name="email" value="<?=$contact['email']?>" id="email" required><br>
                    <input class="form-control form-control-sm" placeholder="Phone number" type="text" name="phone" value="<?=$contact['phone']?>" id="phone"><br>
                    <input class="form-control form-control-sm" placeholder="Title" type="text" name="title" value="<?=$contact['title']?>" id="title"><br>
                    <?php echo csrf_token_tag(); ?>
                    <input class="btn btn-primary btn-sm" type="submit" value="Update">
                    <a href="index.php" type="button" class="btn btn-warning btn-sm">Cancel</a>
                    
                </form>
            </div>
        </div>
        </div>
        <div class="col-md-7 col-sm-12 col-xs-12">
        </div>        
    </div>
</div>
</body>
</html>