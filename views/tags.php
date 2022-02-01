<?php require __DIR__ . '/top2.php' ?>

<div class="container">
    <div class="row">
        <?php foreach($tags as $tag) : ?>
        <!-- tago pradzia -->
        <div class="col-4">
            <div class="card m-2">
                <div class="card-body">
                    <h5 class="card-title"><?= $tag['title'] ?></h5>
                    <form action="<?= URL.'tags/update/'.$tag['id'] ?>" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" value="<?= $tag['title'] ?>">
                        </div>
                        <button class="btn btn-primary">Redaguoti</button>
                    </form>
                    <form action="<?= URL.'tags/delete/'.$tag['id'] ?>" method="post" class="mt-1">
                        <button class="btn btn-danger">Trinti</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- tago pabaiga -->
        <?php endforeach ?>
    </div>
</div>

<?php require __DIR__ . '/bottom2.php' ?>