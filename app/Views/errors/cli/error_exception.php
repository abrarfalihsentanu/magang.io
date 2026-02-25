An uncaught Exception was encountered

Type: <?= get_class($exception) ?>

Message: <?= $message ?>

Filename: <?= $exception->getFile() ?>

Line Number: <?= $exception->getLine() ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE) : ?>

    Backtrace:
    <?php foreach ($exception->getTrace() as $i => $error) : ?>
        <?= $i + 1 ?>. <?= $error['class'] ?? '' ?><?= $error['type'] ?? '' ?><?= $error['function'] ?? '' ?>()
        <?= $error['file'] ?? '[internal function]' ?>:<?= $error['line'] ?? '' ?>

    <?php endforeach ?>

<?php endif ?>