<?php


enum Status {
    case COMPLETED;
    case NOT_COMPLETED;

}
class TaskManager {

    private string $filename;

    private array $tasks;


    /**
     * @throws Exception
     */
    public function __construct(string $filename) {
        if (!file_exists($filename)) {
            throw new Exception("Файл $filename не існує");
        }

        $this->filename = $filename;

        $content = file_get_contents($this->filename);
        $this->tasks = unserialize($content);
        if (!$this->tasks) {
            $this->tasks = [];
        }

    }

    /**
     * @throws Exception
     */
    public function addTask(string $taskName, int $priority): void
    {

        $task = [
            "name" => $taskName,
            "priority" => $priority,
            "status" => Status::NOT_COMPLETED,
            "id" => uniqid()
       ];

        $found = false;
        foreach ($this->tasks as $t) {
            if ($t["name"] == $taskName && $t["priority"] == $priority) {
                $found = true;
                break;
            }
        }

        if (!$found) {

            $this->tasks[] = $task;

            $this->saveTasks();
        }
    }


    /**
     * @throws Exception
     */
    public function deleteTask(string $taskId): void
    {
        $taskIndex = $this->findTaskIndexById($taskId);

        if ($taskIndex !== false) {
            unset($this->tasks[$taskIndex]);
            $this->tasks = array_values($this->tasks);
            $this->saveTasks();
        } else {
            throw new Exception("Завдання з ID $taskId не знайдено.");
        }
    }

    private function findTaskIndexById(string $taskId): int|false
    {
        foreach ($this->tasks as $index => $task) {
            if ($task["id"] == $taskId) {
                return $index;
            }
        }

        return false;
    }

    public function getTasks():array {

        $tasks = $this->tasks;

        usort($tasks, function($a, $b) {
            return $b["priority"] - $a["priority"];
        });

        return $tasks;
    }


    /**
     * @throws Exception
     */
    public function completeTask(string $taskId): void
    {
        for ($i = 0; $i < count($this->tasks); $i++) {

            if ($this->tasks[$i]["id"] == $taskId) {

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
        $content = serialize($this->tasks);
        file_put_contents($this->filename, $content);

    }
}