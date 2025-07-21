<?php
include "db_connect.php";

try {
    // loading posts
    $posts_json = file_get_contents('https://jsonplaceholder.typicode.com/posts');
    $posts = json_decode($posts_json, true);

    $posts_count = 0;
    $stmt_post = $pdo->prepare("INSERT INTO posts (id, user_id, title, body) VALUES (?, ?, ?, ?)");
    
    foreach ($posts as $post) {
        $stmt_post->execute([$post['id'], $post['userId'], $post['title'], $post['body']]);
        $posts_count++;
    }

    // loading comments
    $comments_json = file_get_contents('https://jsonplaceholder.typicode.com/comments');
    $comments = json_decode($comments_json, true);

    $comments_count = 0;
    $stmt_comment = $pdo->prepare("INSERT INTO comments (id, post_id, name, email, body) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($comments as $comment) {
        $stmt_comment->execute([$comment['id'], $comment['postId'], $comment['name'], $comment['email'], $comment['body']]);
        $comments_count++;
    }

    // showing the results
    echo "Загружено $posts_count записей и $comments_count комментариев\n";

} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>