<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f9;
            color: #566a7f;
            padding: 2rem;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ff3e1d;
            margin-bottom: 0.25rem;
        }

        h1 span {
            font-weight: 400;
            font-size: 1rem;
            color: #697a8d;
        }

        p.lead {
            font-size: 1.1rem;
            color: #566a7f;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #fff;
            border-left: 4px solid #ff3e1d;
            border-radius: 0.25rem;
        }

        .source {
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .source strong {
            color: #566a7f;
        }

        .source span {
            color: #697a8d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 0.375rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        table th {
            background: #696cff;
            color: #fff;
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        table td {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #d9dee3;
            font-size: 0.875rem;
            color: #697a8d;
        }

        table tr:nth-child(even) {
            background-color: #f5f5f9;
        }

        pre {
            background: #2b2c40;
            color: #d5dae2;
            padding: 1rem;
            border-radius: 0.375rem;
            overflow-x: auto;
            font-size: 0.8125rem;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container">

        <h1><?= get_class($exception) ?> <span>#<?= $exception->getCode() ?></span></h1>
        <p class="lead"><?= nl2br(esc($message)) ?></p>

        <div class="source">
            <strong><?= esc($exception->getFile()) ?></strong>
            <span>: <?= $exception->getLine() ?></span>
        </div>

        <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE) : ?>
            <h2 style="font-size: 1.25rem; margin-bottom: 0.75rem; color: #566a7f;">Backtrace</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Function</th>
                        <th>File</th>
                        <th>Line</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exception->getTrace() as $i => $error) : ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc(($error['class'] ?? '') . ($error['type'] ?? '') . ($error['function'] ?? '') . '()') ?></td>
                            <td><?= esc($error['file'] ?? '[internal function]') ?></td>
                            <td><?= $error['line'] ?? '' ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>

    </div>
</body>

</html>