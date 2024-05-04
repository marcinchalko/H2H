<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ContactMessage;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\Email;

class EntityPersistedListener
{

    public function __construct(private MessageBusInterface $bus) {}

    public function postPersist(PostPersistEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ContactMessage) {
            $this->bus->dispatch(new Email('john.doe@domain.ltd'));
        }
    }
}
