<?php 

declare(strict_types=1);

namespace App\Service;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use App\Service\ValidationService;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\ORM\EntityManagerInterface;

class ContactMessageService
{
    public function __construct(
        private ContactMessageRepository $contactMessageRepository, 
        private ValidationService $validator, 
        private EntityManagerInterface $entityManager)
    {}

    public function get($page, $perPage): Pagerfanta
    {
        $queryBuilder = $this->contactMessageRepository->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC');

        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($perPage);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
    }
    
    public function save(array $data): ContactMessage
    {
        $message = new ContactMessage();
        $message->setName($data['name']);
        $message->setEmail($data['email']);
        $message->setMessage($data['message']);
        $message->setAgreement($data['agreement']);
        $message->setCreatedAt(new \DateTimeImmutable);

        $this->validator->validate($message);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
}