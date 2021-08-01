<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository extends BaseRepository
{
    private function orderByFields($orderBy): bool
    {
        if (isset($orderBy['field'])) {
            return in_array($orderBy['field'], ['is_complete', 'username', 'email']);
        }
        return false;

    }


    public function getTasks($page = 0, $perPage = 3, $orderBy = []): array
    {
        $statement = self::$pdo->prepare(sprintf("SELECT * from tasks %s limit :perPage offset :page",
            $this->orderByFields($orderBy) ? " order by " . $orderBy['field'] . ($orderBy['direction'] == 'asc' ? ' asc' : ' desc')
                : ' order by id desc'
        ));


        $queryParams = [
            ":perPage" => $perPage,
            ":page" => $page == 1 ? 0 : ($page - 1) + $perPage -1];

        $statement->execute($queryParams);

        $count = self::$pdo->query("SELECT count(*) from tasks")->fetch()['count'];
        $taskArray = $statement->fetchAll();
        $tasks = [];

        foreach ($taskArray as $task) {
            $tasks[] = Task::fromArray($task);
        }
        return ["tasks" => $tasks, 'count' => $count];
    }

    public function getTask($id)
    {
        $statement = self::$pdo->prepare("SELECT * from tasks where id=:id");
        $statement->execute([
            ":id" => $id
        ]);

        return Task::fromArray($statement->fetch());
    }

    public function setTask(Task $task)
    {
        $sql = 'INSERT INTO tasks(username,email,description) VALUES(:username,:email,:description)';

        $statement = self::$pdo->prepare($sql);

        $statement->execute([
            ':username' => $task->username,
            ':email' => $task->email,
            ':description' => $task->description,
        ]);
    }

    public function updateDescription($id, $description)
    {
        $sql = 'UPDATE tasks SET description = :description  where id = :id';

        $statement = self::$pdo->prepare($sql);

        $statement->execute([
            ':id' => $id,
            ':description' => $description,
        ]);
    }

    public function completeTask($id)
    {
        $sql = 'UPDATE tasks SET is_complete = true  where id = :id';

        $statement = self::$pdo->prepare($sql);

        $statement->execute([
            ':id' => $id,
        ]);
    }

    public function unCompleteTask($id)
    {
        $sql = 'UPDATE tasks SET is_complete = false  where id = :id';

        $statement = self::$pdo->prepare($sql);

        $statement->execute([
            ':id' => $id,
        ]);
    }
}