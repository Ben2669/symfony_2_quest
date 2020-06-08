<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Anthony Hopkins',
        'Jeffrey Wright',
        'Evan Rachel Woods',
        'Pedro Pascal',
        'Eva Green',
    ];

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return [ProgramFixtures::class];
    }

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        // TODO: Implement load() method.
        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->addProgram($this->getReference('program_'.rand(0, 5)));
            $manager->persist($actor);
            $this->addReference('actor_'.$key, $actor);

        }
        for ($i = 0; $i < 50; $i++) {
            $actor = new Actor();
            $faker = Faker\Factory::create();
            $actor->setName($faker->name());
            $actor->addProgram($this->getReference('program_'.rand(0,5)));
            $manager->persist($actor);
            $this->addReference('actor_'.($i + 7), $actor);
        }

        $manager->flush();
    }
}