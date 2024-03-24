<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $seedFilePath = __DIR__ . '/seed.json';
        $jsonData = file_get_contents($seedFilePath);
        $quizzes = json_decode($jsonData, true)['quizzes'];

        foreach ($quizzes as $quizData) {
            $quiz = new Quiz();
            $quiz->setTitle($quizData['title']);

            $manager->persist($quiz);

            foreach ($quizData['questions'] as $questionData) {
                $question = new Question();
                $question->setText($questionData['text']);

                $quiz->addQuestion($question);

                $manager->persist($question);

                foreach ($questionData['answers'] as $answerData) {
                    $answer = new Answer();
                    $answer->setText($answerData['text']);
                    $answer->setIsCorrect($answerData['is_correct']);

                    $question->addAnswer($answer);

                    $manager->persist($answer);
                }
            }
        }

        $manager->flush();
    }
}
