<?php
include "db_connect.php";

// search query 
$search_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_term']) && strlen($_POST['search_term']) >= 3) {
    try {
        $search_term = '%' . $_POST['search_term'] . '%';
        $stmt = $pdo->prepare("
            SELECT p.title, c.body 
            FROM posts p
            JOIN comments c ON p.id = c.post_id
            WHERE c.body LIKE ?
        ");
        $stmt->execute([$search_term]);
        $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Ошибка подключения к БД: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск записей по комментариям</title>
    <style>
        body { 
            font-family: Arial, sans-serif; margin: 20px; 
        }
        .search-form { 
            margin-bottom: 20px; 
        }
        .search-results { 
            margin-top: 20px; 
        }
        .result-item { 
            margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; 
        }
        .post-title { 
            font-weight: bold; margin-bottom: 5px; 
        }
        .comment-body { 
            color: #555; 
        }
    </style>
</head>
<body>
    <h1>Поиск записей по комментариям</h1>
    
    <div class="search-form">
        <form method="POST">
            <input type="text" name="search_term" placeholder="Введите минимум 3 символа" 
                   value="<?= htmlspecialchars($_POST['search_term'] ?? '') ?>" required minlength="3">
            <button type="submit">Найти</button>
        </form>
    </div>

    <?php if (!empty($search_results)): ?>
    <div class="search-results">
        <h2>Результаты поиска (<?= count($search_results) ?>):</h2>
        <?php foreach ($search_results as $result): ?>
            <div class="result-item">
                <div class="post-title"><?= htmlspecialchars($result['title']) ?></div>
                <div class="comment-body"><?= htmlspecialchars($result['body']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>Ничего не найдено. Попробуйте другой запрос.</p>
    <?php endif; ?>
</body>
</html>