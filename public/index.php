<?php
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_DATABASE') ?: 'laravel_db';
$user = getenv('DB_USERNAME') ?: 'laravel_user';
$pass = getenv('DB_PASSWORD') ?: 'laravel_pass';

$status = "Connecting...";
$db_ok = false;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS exam_tasks (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255))");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
        $stmt = $pdo->prepare("INSERT INTO exam_tasks (name) VALUES (?)");
        $stmt->execute([$_POST['task']]);
        header("Location: /");
        exit;
    }

    if (isset($_GET['del'])) {
        $stmt = $pdo->prepare("DELETE FROM exam_tasks WHERE id = ?");
        $stmt->execute([$_GET['del']]);
        header("Location: /");
        exit;
    }

    $tasks = $pdo->query("SELECT * FROM exam_tasks ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    $db_ok = true;
    $status = "Connected to MySQL Database Tier Successfully!";
} catch (Exception $e) {
    $status = "Database Connection Failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laravel 3-Tier Application</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background: #f8fafc; color: #1e293b; }
        .box { background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-weight: bold; background: <?= $db_ok ? '#22c55e' : '#ef4444' ?>; color: #fff; margin-bottom: 20px; }
        input[type="text"] { width: 70%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 8px 14px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        ul { list-style: none; padding: 0; margin-top: 20px; }
        li { padding: 8px 0; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; }
        a.del { color: #dc2626; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Laravel Three-Tier Web Application</h2>
        <div class="badge"><?= $status ?></div>
        <form method="POST">
            <input type="text" name="task" placeholder="Enter task name..." required>
            <button type="submit">Add Task</button>
        </form>
        <ul>
            <?php foreach ($tasks as $t): ?>
                <li>
                    <span><?= htmlspecialchars($t['name']) ?></span>
                    <a class="del" href="/?del=<?= $t['id'] ?>">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
