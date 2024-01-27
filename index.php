<?php

declare(strict_types=1);

require_once 'classes/TaskManager.php';

$taskManager = new TaskManager("tasks.txt");

try {
    $taskManager->addTask("Приготовить ужин", 2);
    $taskManager->addTask("Сделать домашнее задание по PHP", 5);
    $taskManager->addTask("Посмотреть фильм", 3);
    $taskManager->addTask("Почитать книгу", 4);
    $taskManager->addTask("Почитать книгу Harry Potter", 10);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
</head>
<body>

<h1>Task List</h1>

<pre>
        <?php print_r($taskManager->getTasks()); ?>
</pre>

<?php

try {
    $taskManager->completeTask("65b556ce05e28");
} catch (Exception $e) {
     echo "Error: " . $e->getMessage();
}

try {
    $taskManager->deleteTask("65b285704b734");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>

<h2>Updated Task List</h2>

<pre>
        <?php print_r($taskManager->getTasks()); ?>
</pre>

</body>
</html>