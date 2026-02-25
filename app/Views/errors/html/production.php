<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whoops!</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            padding: 2rem;
        }

        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #566a7f;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            color: #697a8d;
            margin-bottom: 2rem;
            max-width: 480px;
        }

        a.btn {
            display: inline-block;
            padding: 0.625rem 1.5rem;
            background-color: #696cff;
            color: #fff;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        a.btn:hover {
            background-color: #5f61e6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">&#9888;&#65039;</div>
        <h1>Terjadi Kesalahan</h1>
        <p>Maaf, terjadi kesalahan pada server. Silakan coba lagi nanti atau hubungi administrator jika masalah berlanjut.</p>
        <a href="/" class="btn">Kembali ke Beranda</a>
    </div>
</body>

</html>