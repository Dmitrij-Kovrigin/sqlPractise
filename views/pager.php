<?php
    $pages = ceil($count / $in_one_page);
?>
<div class="col-12">
    <nav aria-label="Page navigation example" class="m-2">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <?php foreach (range(1, $pages) as $page) : ?>
            <li class="page-item"><a class="page-link" href="<?= URL. 'sarasas'.pager($page) ?>"><?= $page ?></a></li>
            <?php endforeach ?>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
</div>