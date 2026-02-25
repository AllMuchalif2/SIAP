<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title><?= lang('Errors.whoops') ?></title>

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
            color: #1f2937;
            padding: 1rem;
        }
        .card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 2rem 1.5rem;
            text-align: center;
        }
        .code {
            margin: 0;
            font-size: 2.5rem;
            line-height: 1;
            color: #111827;
        }
        .text {
            margin: 1rem 0 1.5rem;
            color: #4b5563;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: #fff;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1 class="code">500</h1>
        <p class="text"><?= lang('Errors.weHitASnag') ?></p>
        <a class="btn" href="<?= site_url('/') ?>">Kembali ke /</a>
    </div>

</body>

</html>
