<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\FigType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

    
        $user = new User();
        $user->setName('Rose')
            ->setEmail('rose@test.fr')
            ->setImage($faker->imageUrl($width = 200, $height = 200, 'people'))
            ->setPassword('test');
        $manager->persist($user);
        


        $figTypes = ['Grab', 'Rotation', 'Flip', 'Slide'];

        foreach($figTypes as $figTypeName){
            $figType = new FigType();
            $figType->setName($figTypeName);
            $manager->persist($figType);

            for($i = 1; $i <= 3; $i++){
                $trick = new Trick();
                $trick->setName("Trick n°$i")
                    ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                    ->setImage($faker->imageUrl($width = 800, $height = 600, 'abstract'))
                    ->setFigType($figType)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'));
                $days = (new \DateTime())->diff($trick->getCreatedAt())->days;
                $trick->setEditAt($faker->dateTimeBetween('-' . $days . ' days'));
                $manager->persist($trick);

                

                for($k = 1; $k <= mt_rand(0, 2); $k++){
                    $comment = new Comment();
    
                    /*$days = (new \DateTime())->diff($article->getCreatedAt())->days;
                    */
                    $comment->setUser($user)
                            ->setContent($faker->sentence($nbWords = 6, $variableNbWords = true))
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setTrick($trick);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
