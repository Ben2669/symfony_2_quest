<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return [ProgramFixtures::class];
    }

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $season = new Season();
            $faker = Faker\Factory::create();
            $season->setNumber($faker->numberBetween(1, 10));
            $season->setProgram($this->getReference('program_'.rand(0,5)));
            $season->setYear($faker->numberBetween(2000, 2020));
            $season->setDescription($faker->realText());
            $manager->persist($season);
            $this->addReference('season_'.$i, $season);
        }

        $manager->flush();
    }
}