<?php require __DIR__ . '/top2.php' ?>

<div class="container">
    <div class="row justify-content-md-center mt-5">
        <div class="col-4">
            <div class="card m-2">
                <div class="card-body">
                    <h5 class="card-title">Prisijungimas</h5>
                    <form action="<?= URL.'login' ?>" method="post">
                        <div class="form-group">
                            <label>Vardas:</label>
                            <input type="text" class="form-control" name="user" value="">
                        </div>
                        <div class="form-group">
                            <label>Slaptažodis:</label>
                            <input type="password" class="form-control" name="pass">
                        </div>
                        <button class="btn btn-primary">Prisijungti</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/bottom2.php' ?>