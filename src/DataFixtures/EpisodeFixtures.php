<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slug;

    public function __construct(Slugify $slugify)
    {
        $this->slug = $slugify;
    }

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return [SeasonFixtures::class];
    }

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        for ($i = 0; $i < 500; $i++) {
            $episode = new Episode();
            $faker = Faker\Factory::create();
            $episode->setNumber($faker->numberBetween(1, 10));
            $episode->setSeason($this->getReference('season_'.rand(0,49)));
            $episode->setTitle($faker->sentence());
            $episode->setSlug($this->slug->generate($episode->getTitle()));
            $episode->setSynopsis($faker->realText());
            $manager->persist($episode);
            $this->addReference('episode_'.$i, $episode);
        }

        $manager->flush();
    }
}