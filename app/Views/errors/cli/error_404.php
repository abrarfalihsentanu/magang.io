ERROR: <?= $message ?? '404 - Page Not Found' ?>

<?php if (! empty($message)) : ?>
    <?= $message ?>

<?php endif ?>