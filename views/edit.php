<?php require __DIR__ . '/top2.php' ?>

<div class="container">
    <div class="row">
        <?php foreach($outfits as $outfit) : ?>
        <!-- prekes pradzia -->
        <div class="col-4">
            <div class="card m-2">
                <div class="card-body">
                    <h5 class="card-title"><?= $outfit['color'] ?> <?= $outfit['type'] ?></h5>
                    <p class="card-text">Galutinė Kaina: <?= $outfit['total_price'] ?></p>
                    <form action="<?= URL.'update/'.$outfit['id'] ?>" method="post">
                        <div class="form-group">
                            <label>Kaina:</label>
                            <input type="text" class="form-control" name="price" value="<?= $outfit['price'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Nuolaida:</label>
                            <input type="text" class="form-control" name="discount" value="<?= $outfit['discount'] ?>">
                        </div>
                        <?php foreach($outfit['sizes_amounts'] as $size => $amount) : ?>
                        <div class="form-group">
                            <label><?= $size ?> kiekis:</label>
                            <input type="text" class="form-control" name="size[<?= $size ?>]" value="<?= $amount ?>">
                            <input type="checkbox" name="delete_size[]" value="<?= $size ?>"> Trinti
                        </div>
                        <?php endforeach ?>
                        <button class="btn btn-primary">Redaguoti</button>
                    </form>
                    <form action="<?= URL.'add-size/'.$outfit['id'] ?>" method="post" class="mt-5">
                        <div class="form-group">
                            <label>Naujas Dydis:</label>
                            <input type="text" class="form-control" name="new_size">
                        </div>
                        <button class="btn btn-primary">Pridėti dydį</button>
                    </form>

                    <?php if($outfit['tags_list'][0]) : ?>
                    <form action="<?= URL.'remove-tag/'.$outfit['id'] ?>" method="post" class="mt-5">
                        <?php foreach($outfit['tags_list'] as $tag) : ?>
                        <span class="badge badge-pill badge-info"><?= $tag ?>
                            <input type="checkbox" name="remove_tag[]" value="<?= $tag ?>">
                        </span>
                        <?php endforeach ?>
                        <div>
                            <button class="btn btn-primary mt-2">Trint pažymėtus</button>
                        </div>
                    </form>
                    <?php endif ?>


                    <form action="<?= URL.'add-tag/'.$outfit['id'] ?>" method="post" class="mt-5">
                        <div class="form-group">
                            <select name="tag" class="form-control">
                                <option value="0">Pasirinkite tagą</options>
                                    <?php foreach ($allTags as $tag) : ?>
                                <option value="<?= $tag['id'] ?>"><?= $tag['title'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Arba pridėkite naują:</label>
                            <input type="text" class="form-control" name="new_tag">
                        </div>
                        <button class="btn btn-primary">Pridėti tagą</button>
                    </form>


                </div>
            </div>
        </div>
        <!-- prekes pabaiga -->
        <?php endforeach ?>
    </div>
</div>

<?php require __DIR__ . '/bottom2.php' ?>