<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\FigType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $figTypes = ['Grab', 'Rotation', 'Flip', 'Slide'];

        foreach($figTypes as $figTypeName){
            $figType = new FigType();
            $figType->setName($figTypeName);
            $manager->persist($figType);

            for($i = 1; $i <= 3; $i++){
                $trick = new Trick();
                $trick->setName("Trick n°$i")
                    ->setDescription("La description du trick n°$i")
                    ->setImage("http://placehold.it/200x100")
                    ->setFigType($figType)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'));
                $days = (new \DateTime())->diff($trick->getCreatedAt())->days;
                $trick->setEditAt($faker->dateTimeBetween('-' . $days . ' days'));
                $manager->persist($trick);


            }

        }

        $manager->flush();
    }
}
