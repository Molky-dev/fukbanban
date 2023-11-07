<?php


namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/api/v1/")]
class APITodoController extends AbstractController {
    #[Route("tasks")]
    public function tasks(TaskRepository $taskRepository): JsonResponse {
        $tasks = $taskRepository->findAll();
        $data = [];
        $usersId = [];
        foreach ($tasks as $task) {
            foreach($task->getUsers() as $user) {
                $usersId[] = $user->getId();
            }
            $data[] = [
                "id" => $task->getId(),
                "title" => $task->getName(),
                "description" => $task->getDescription(),
                "project" => $task->getProject()->getName(),
                "users" => $usersId,
                "createDate" => $task->getCreateDate(),
                "editDate" => $task->getEditDate(),
                "status" => $task->getStatus()->getType(),
                "status_id" => $task->getStatus()->getId(),
                "is_granted" => $this->isGranted( "TASK_DELETE", $task)

            ];
        }

        return new JsonResponse($data, 200, ['Access-Control-Allow-Origin' => '*']);

    }

    #[Route("tasks/{id}")]
    public function tasksByProject(TaskRepository $taskRepository, ProjectRepository $projectRepository, int $id): JsonResponse {
        $tasks = $taskRepository->findBy(["project" => $projectRepository->find($id)]);
        $data = [];
        $usersId = [];
        foreach ($tasks as $task) {
            foreach($task->getUsers() as $user) {
                $usersId[] = $user->getId();
            }
            $data[] = [
                "id" => $task->getId(),
                "title" => $task->getName(),
                "description" => $task->getDescription(),
                "project" => $task->getProject()->getName(),
                "users" => $usersId,
                "createDate" => $task->getCreateDate(),
                "editDate" => $task->getEditDate(),
                "status" => $task->getStatus()->getType(),
                "status_id" => $task->getStatus()->getId(),
                "is_granted" => $this->isGranted( "TASK_DELETE", $task)


            ];
        }
        return new JsonResponse($data, 200, ['Access-Control-Allow-Origin' => '*']);

    }

    #[Route("tasks/{taskId}/status/{taskStatus}")]
    public function update(TaskRepository $taskRepository, int $taskId, int $taskStatus, StatusRepository $statusRepository, EntityManagerInterface $entityManager): JsonResponse {
        $task = $taskRepository->find($taskId);
        $task->setStatus(array_values($statusRepository->findBy(["id" => $taskStatus]))[0]);
        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse($data, 200, ['Access-Control-Allow-Origin' => '*']);

    }

    #[Route("tasks/delete/{taskId}")]
    public function delete(TaskRepository $taskRepository, int $taskId, EntityManagerInterface $entityManager): JsonResponse {

        $task = array_values($taskRepository->findBy(["id" => $taskId]))[0];
        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse($data, 200, ['Access-Control-Allow-Origin' => '*']);

    }

}