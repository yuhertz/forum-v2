<?php
session_start();

$log_file_path = 'log.txt';

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple authentication check
    if ($username === 'Hertz' && $password === 'hertz2323') {
        $_SESSION['username'] = htmlspecialchars($username);
    } else {
        echo "Invalid username or password.";
    }
}

// Process the logout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
}

// Process the message form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $text = $_POST['text'] ?? '';

    if (!empty($_SESSION['username']) && !empty($text)) {
        // Open the file in append mode
        $log_file = fopen($log_file_path, 'a');

        if ($log_file) {
            $username = $_SESSION['username'];
            $text = htmlspecialchars($text);

            // Append the username and message to the file
            fwrite($log_file, "$text\n$username\n\n");

            // Close the file
            fclose($log_file);
            echo "Message logged successfully.";
        } else {
            echo "Error opening log file.";
        }
    } else {
        echo "Username or message is empty.";
    }
}

// Read and display the log file in reverse order
$log_lines = array_reverse(file($log_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #61a1ff;
            padding: 10px;
            text-align: center;
            color: #fff;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .message-container {
            border-bottom: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        .message-container p strong {
            font-weight: bold;
            text-decoration: underline;
        }

        .message-container p span {
            display: block;
            margin-top: 5px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .logout-btn {
            background-color: #d9534f;
        }

        .logout-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Forum</h1>
    </header>

    <main>
        <?php if (!isset($_SESSION['username'])): ?>
            <h2>Login</h2>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required autocomplete="off">
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required autocomplete="off">
                <br>
                <button type="submit" name="login">Login</button>
            </form>
        <?php else: ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <form method="post" action="">
                <label for="text">Your Message:</label>
                <input type="text" id="text" name="text" required autocomplete="off">
                <br>
                <button type="submit" name="submit">Post</button>
            </form>

            <form method="post" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>

            <h2>Messages</h2>

            <?php foreach ($log_lines as $index => $line): ?>
                <?php if ($index % 2 === 0): ?>
                    <div class="message-container">
                        <p><strong><?php echo htmlspecialchars($line); ?></strong></p>
                <?php else: ?>
                        <span><?php echo htmlspecialchars($line); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
