<?php if ($pager->hasMore('group1')) : ?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- Display previous page link -->
            <?php if ($pager->hasPrevious()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Display page number links -->
            <?php foreach ($pager->links('group1') as $link) : ?>
                <li <?= $link['active'] ? 'class="page-item active"' : 'class="page-item"' ?>>
                    <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                </li>
            <?php endforeach; ?>

            <!-- Display next page link -->
            <?php if ($pager->hasNext()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>