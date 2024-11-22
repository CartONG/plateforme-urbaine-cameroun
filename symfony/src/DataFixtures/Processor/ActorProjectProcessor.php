<?php
namespace App\DataFixtures\Processor;

use App\Entity\Actor;
use App\Entity\Project;
use Fidry\AliceDataFixtures\ProcessorInterface;

final class ActorProjectProcessor  implements ProcessorInterface
{
    public function preProcess(string $fixtureId, $object): void
    {
        // For testing purpose
        static $callCount = 0;
        if ($object instanceof Actor) {
            $callCount++;
            $isValidated = $callCount % 4 !== 0;
            $object->setIsValidated($isValidated);
        }
    }

    public function postProcess(string $fixtureId, $object): void
    {
        if ($object instanceof Project) {
            $actor = $object->getActor();
            if ($actor instanceof Actor) {
                $actor->addProject($object);
            }
        }

        if ($object instanceof Actor) {
            foreach ($object->getProjects() as $project) {
                $project->setActor($object);
            }
        }
    }
}