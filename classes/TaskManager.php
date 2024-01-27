<?php

require_once './classes/Status.php';

class TaskManager {

    private string $filename;
    private array $tasks;

    /**
     * @throws Exception
     */
    public function __construct(string $filename) {

        $this->setFilename($filename);
        $content = file_get_contents($this->getFilename());
        $this->setTasks(unserialize($content) ?: []);
    }

    /**
     * @throws Exception
     */
    public function addTask(string $taskName, int $priority): void {
        $task = [
            "name" => $taskName,
            "priority" => $priority,
            "status" => Status::NOT_COMPLETED,
            "id" => uniqid()
        ];

        if (!$this->isTaskExists($taskName, $priority)) {
            $this->setTasks(array_merge($this->getTasks(), [$task]));
            $this->saveTasks();
        }
    }

    /**
     * @throws Exception
     */
    public function deleteTask(string $taskId): void {
        $taskIndex = $this->findTaskIndexById($taskId);

        if ($taskIndex !== false) {
            unset($this->tasks[$taskIndex]);
            $this->setTasks(array_values($this->getTasks()));
            $this->saveTasks();
        } else {
            throw new Exception("Завдання з ID $taskId не знайдено.");
        }
    }

    private function findTaskIndexById(string $taskId): int|false {
        foreach ($this->getTasks() as $index => $task) {
            if ($task["id"] == $taskId) {
                return $index;
            }
        }

        return false;
    }


    /**
     * @throws Exception
     */
    public function completeTask(string $taskId): void {
        foreach ($this->getTasks() as $i => $task) {
            if ($task["id"] == $taskId) {
                $this->tasks[$i]["status"] = Status::COMPLETED;
                $this->saveTasks();
                break;
            }
        }
    }

    /**
     * @throws Exception
     */
    private function saveTasks(): void {
        $content = serialize($this->getTasks());
        file_put_contents($this->getFilename(), $content);
    }

    private function isTaskExists(string $taskName, int $priority): bool {
        foreach ($this->getTasks() as $task) {
            if ($task["name"] == $taskName && $task["priority"] == $priority) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getFilename(): string {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @throws Exception
     */
    public function setFilename(string $filename): void {
        $this->filename = $filename;
        if (!file_exists($filename)) {
            throw new Exception("Файл $filename не існує");
        }
    }

    /**
     * @return array
     */
    public function getTasks(): array {
        $tasks = $this->tasks;

        usort($tasks, function ($a, $b) {
            return $b["priority"] - $a["priority"];
        });
        return $this->tasks;
    }

    /**
     * @param array $tasks
     */
    public function setTasks(array $tasks): void {
        $this->tasks = $tasks;
    }
}