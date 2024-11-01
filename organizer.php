<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

class Organizer {
    private $tasks = [];

    public function __construct() {
        if (isset($_SESSION['tasks'])) {
            $this->tasks = $_SESSION['tasks'];
        }
    }

    public function addTask($task, $date) {
        if (!$this->isValidDate($date)) {
            throw new InvalidArgumentException("Неверный формат даты: $date.");
        }

        $this->tasks[] = [
            'task' => $task,
            'date' => $date,
            'canceled' => false
        ];
        $this->saveTasksToSession();
        return "<p class='success'>Задача добавлена: $task на дату: $date</p>";
    }

    public function printTasksByRange($startDate, $endDate) {
        $tasksToPrint = [];
        $output = "<h2>Задачи с $startDate по $endDate:</h2><ul>";

        foreach ($this->tasks as $index => $task) {
            $taskDate = new DateTime($task['date']);
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
            $end->setTime(23, 59, 59);

            if (!$task['canceled'] && $taskDate >= $start && $taskDate <= $end) {
                $output .= "<li>Задача: {$task['task']} (Индекс: $index), Дата: {$task['date']}</li>";
            }
        }
        $output .= "</ul>";

        return $output;
    }

    public function cancelTask($taskIndex) {
        if (!is_numeric($taskIndex) || $taskIndex < 0 || $taskIndex >= count($this->tasks)) {
            throw new OutOfBoundsException("Задача с индексом $taskIndex не найдена.");
        }

        $this->tasks[$taskIndex]['canceled'] = true;
        $this->saveTasksToSession();
        return "<p class='success'>Задача с индексом $taskIndex отменена.</p>";
    }

    private function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function saveTasksToSession() {
        $_SESSION['tasks'] = $this->tasks;
    }
}

$organizer = new Organizer();
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'addTask':
            echo $organizer->addTask($_POST['task'], $_POST['date']);
            break;
        case 'printTasks':
            echo $organizer->printTasksByRange($_POST['startDate'], $_POST['endDate']);
            break;
        case 'cancelTask':
            echo $organizer->cancelTask((int)$_POST['taskIndex']);
            break;
        default:
            echo "<p class='error'>Неизвестное действие.</p>";
            break;
    }
} catch (Exception $e) {
    echo "<p class='error'>Ошибка: " . $e->getMessage() . "</p>";
}