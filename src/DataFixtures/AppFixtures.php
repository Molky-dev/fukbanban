<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();
        $users = [];
        $projects = [];
        $statuses = [];
        $tasks = [];

        $status = new Status();
        $status->setType("Backlog");
        $manager->persist($status);

        $status2 = new Status();
        $status2->setType("Running");
        $manager->persist($status2);

        $status3 = new Status();
        $status3->setType("Evaluating");
        $manager->persist($status3);

        $status4 = new Status();
        $status4->setType("In Progress");
        $manager->persist($status4);

        $status5 = new Status();
        $status5->setType("Live");
        $manager->persist($status5);

        $statuses[] = $status;
        $statuses[] = $status2;
        $statuses[] = $status3;
        $statuses[] = $status4;
        $statuses[] = $status5;


        for($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setMail($faker->email());
            $user->setUsername($faker->userName());
            $user->setPassword($this->hasher->hashPassword($user, "123456"));
            $user->setRole("ROLE_USER");
            $manager->persist($user);
            $users[] = $user;
        }
        for($i=0;$i<10;$i++) {
            $project = new Project();
            $project->setName($faker->sentence());
            $project->setDescription($faker->text());
            $project->setCreateDate($faker->dateTime());
            $project->setEditDate($faker->dateTime());
            $project->setUserId($users[$faker->numberBetween(0, count($users) - 1)]);
            $manager->persist($project);
            $projects[] = $project;

            for($j = 0;$j < 5; $j ++) {
                $task = new Task();
                $task->setName($faker->sentence());
                $task->setDescription($faker->text());
                $task->setCreateDate($faker->dateTime());
                $task->setEditDate($faker->dateTime());
                $task->setProject($projects[$i]);
                if($j == 0)
                    $task->addUser($users[$faker->numberBetween(0, count($users) - 1)]);
                $task->setStatus($statuses[$faker->numberBetween(0, count($statuses) - 1)]);
                $manager->persist($task);
            }
        }








        $manager->flush();
    }
}
