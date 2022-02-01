<?php require __DIR__ . '/top.php' ?>
<div class="container">
    <div class="row">
        <?php _d($outfits) ?>
        <?php foreach($outfits as $outfit) : ?>
        <!-- prekes pradzia -->
        <div class="col-4">
            <div class="card m-2">
                <div class="card-body">
                    <h5 class="card-title"><?= $outfit['color'] ?> <?= $outfit['type'] ?></h5>
                    <?php foreach($outfit['tags_list'] as $tag) : ?>
                    <span class="badge badge-pill badge-info"><?= $tag ?></span>
                    <?php endforeach ?>
                    <p class="card-text">Kaina: <?= $outfit['total_price'] ?> <del><?= $outfit['price'] ?></del></p>
                    <form action="<?= URL. 'pirkti' ?>" method="post">
                        <div class="form-group">
                            <select name="size" class="form-control">
                                <?php foreach($outfit['sizes_amounts'] as $size => $amount) : ?>
                                <option value="<?= $size ?>"><?= $size ?> Liko: <?= $amount ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <button class="btn btn-primary" name="id" value="<?= $outfit['id'] ?>">Pirkti</button>
                        <input type="text" style="width:30px;" name="count">
                    </form>
                </div>
            </div>
        </div>
        <!-- prekes pabaiga -->
        <?php endforeach ?>
    </div>
</div>

<?php require __DIR__ . '/bottom.php' ?>