<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
        }

        .key-verifcation {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #0f2658;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="key-verifcation">
        <div class="key">
            <h1>Validate Lincense Key</h1>
            <form action="{{ route('check.variable') }}" method="POST">
                @csrf
                <label for="key">Enter Key:</label>
                <input type="text" id="key" name="key" required autocomplete="off">
                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>

</html>