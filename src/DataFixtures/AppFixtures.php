<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Users;
use Faker\Factory;
use App\Entity\Questions;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        
        // Users
        $users = [];
        for ($i = 1; $i < 13; $i++) {
            $user = new Users();
            $user->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setPseudo($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPassword('password')
                ->setAgreeTerms(true);
            $users[] = $user;
            $manager->persist($user);
        }

        //Categories
        $categories=[];
        for($i = 1; $i < 10; $i++){
            $category=new Category();
            $category->setName($this->faker->word(10));
            $categories[]=$category;
            $manager->persist($category);

        }


        //Questions
        $questions = [];
        for ($i = 1; $i < 26; $i++) {
            $question = new Questions();
            $question->setMainTitle($this->faker->sentence(2))
                    ->setDescription($this->faker->sentence(10))
                    ->setContent($this->faker->paragraph(4))
                    ->setAuthor($users[mt_rand(1, count($users) - 1)])
                    ->setCategory($categories[mt_rand(1, count($categories) - 1)]);
            $questions[] = $question;
            $manager->persist($question);
        }

        
         //Réponses
         $answers = [];
         for ($i = 1; $i < 50; $i++) {
            $answer = new Answer();
            $answer->setContent($this->faker->paragraph(5))
                    ->setAuth($users[mt_rand(1, count($users) - 1)])
                    ->setQuestion($questions[mt_rand(1, count($questions) - 1)]);
                   
            $answers[] = $answer;
            $manager->persist($answer);
         }

       

        $manager->flush();

       
    }
}
