<?php

namespace App\Controller;


use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController {

    #[Route("/", name:"app_todo_index", methods:["GET"])]
    public function index(StatusRepository $statusRepository, TaskRepository $taskRepository, ProjectRepository $projectRepository): Response {
        return $this->render("todo/index.html.twig", [
            "statues" => $statusRepository->findAll(),
            "tasks" => $taskRepository->findAll(),
            "projects" => $projectRepository->findAll()

        ]);
    }





}
