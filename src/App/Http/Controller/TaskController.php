<?php

namespace App\Http\Controller;

use App\Http\Request\Path;
use App\Http\Request\Validator;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Framework\Http\Session\SessionFlash;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;

class TaskController extends BaseController
{

    public TaskRepository $repository;

    public function __construct($render)
    {
        parent::__construct($render);
        $this->repository = new TaskRepository();
    }

    public function index(ServerRequest $request): HtmlResponse
    {
        $page = $request->getQueryParams()['page'] ?? 1;
        $query = $request->getQueryParams()['orderBy'] ?? [];


        $tasks = $this->repository->getTasks($page, 3, $query);
        return new HtmlResponse($this->render("app/home", [
            'tasks' => $tasks['tasks'],
            'count' => $tasks['count'],
            'user' => isset($_SESSION['login']) ?: null]));
    }

    /**
     * @param ServerRequest $request
     * @return HtmlResponse|RedirectResponse
     */
    public function store(ServerRequest $request)
    {
        try {
            $task = Task::fromArray($request->getParsedBody());

            $validator = new Validator();
            $validator->rules = [
                'username' => 'required',
                'email' => 'required|email'
            ];
            if ($validator->validate()) {
                $this->repository->setTask($task);
                return new RedirectResponse('/');
            }
            SessionFlash::error(implode("\n", $validator->errors->all()));
            return new RedirectResponse('/');

        } catch (\Exception $e) {
            return new HtmlResponse($this->render("error/error", ["error" => $e->getMessage()]));
        }


    }

    public function update(ServerRequest $request)
    {
        try {
            $id = $request->getAttribute('id');
            $description = $request->getParsedBody()['description'];

            $validator = new Validator();
            $validator->rules = [
                'description' => 'required',
            ];
            if ($validator->validate()) {
                $this->repository->updateDescription($id, $description);
                SessionFlash::success("Описание обновлено");
                return new RedirectResponse('/');
            }
            SessionFlash::error("Поле описание объязательно");
            return new RedirectResponse(Path::generate('task'));
        } catch (\Exception $e) {
            return new HtmlResponse($this->render("error/error", ["error" => $e->getMessage()]));
        }
    }

    public function complete(ServerRequest $request): RedirectResponse
    {
        $id = $request->getAttribute('id');
        $this->repository->completeTask($id);
        return new RedirectResponse(Path::generate('task'));
    }

    public function unComplete(ServerRequest $request): RedirectResponse
    {
        $id = $request->getAttribute('id');
        $this->repository->unCompleteTask($id);
        return new RedirectResponse(Path::generate('task'));
    }
}
