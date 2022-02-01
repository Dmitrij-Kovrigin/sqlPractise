<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= URL ?>css/app.css">
    <title>Rūbai</title>
</head>

<body>

    <?php if(!empty($messages)) : ?>
    <div class="container">
        <div class="row justify-content-md-center mt-5">
            <div class="col-7">
            <?php foreach($messages as $message) : ?>
                <div class="alert alert-<?= $message['type'] ?>" role="alert">
                    <?= $message['msg'] ?>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php endif ?>
    
    <?php if($appUser) : ?>
    <form action="<?= URL. 'logout' ?>" method="post" class="m-3">
        <button class="btn btn-primary">Atsijungti <?= $appUser ?></button>
    </form>
    <?php endif ?>