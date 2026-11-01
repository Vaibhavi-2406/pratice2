Sure — here is a very simple PHP + MySQL version with no CSS and no external JavaScript file.

Just plain HTML form + PHP backend.

1) Install PHP and MySQL

```bash
sudo apt update
sudo apt install -y mysql-server php php-mysql php-cli
```

2) Create database and table

```bash
sudo mysql -u root <<'SQL'
CREATE DATABASE IF NOT EXISTS split_deploy;
CREATE USER IF NOT EXISTS 'appuser'@'localhost' IDENTIFIED BY 'StrongPass123!';
GRANT ALL PRIVILEGES ON split_deploy.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;

USE split_deploy;
CREATE TABLE IF NOT EXISTS contact_submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SQL
```

3) Create folder and files

```bash
mkdir -p ~/split-php
cd ~/split-php
```

Create `index.php`:

```php
<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($name == "" || $email == "" || $message == "") {
        $error = "Please fill all fields.";
    } else {
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=split_deploy;charset=utf8mb4", "appuser", "StrongPass123!");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);

            $success = "Your message was saved successfully!";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
</head>
<body>
    <h1>Contact Form</h1>

    <?php if ($error != ""): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success != ""): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>
            <label>Name:</label><br>
            <input type="text" name="name">
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email">
        </p>

        <p>
            <label>Message:</label><br>
            <textarea name="message" rows="5"></textarea>
        </p>

        <p>
            <button type="submit">Submit</button>
        </p>
    </form>
</body>
</html>
```

4) Run the app

```bash
cd ~/split-php
php -S localhost:8000
```

Open browser:

```text
http://localhost:8000
```

This is the simplest beginner version:
- one PHP file
- plain HTML form
- data saved to MySQL

If you want a slightly better version, I can next give you:
- PHP form + display saved entries
- PHP + MySQL + separate frontend/backend folders
- PHP with no database password in the file and more secure version
