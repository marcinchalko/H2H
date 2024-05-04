<?php 

declare(strict_types=1);

namespace App\Service;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use App\Service\ValidationService;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Elastica\Query;

class ContactMessageService
{
    public function __construct(
        private PaginatedFinderInterface $finder,
        private ContactMessageRepository $contactMessageRepository, 
        private ValidationService $validator, 
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator
    ) {}

    public function get($page, $perPage): Pagerfanta
    {
        $pagerfanta = new Pagerfanta(new QueryAdapter(
            $this->contactMessageRepository->createQueryBuilder('m')->orderBy('m.id', 'DESC')
        ));
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

    public function getRepository($page, $perPage)
    {
        return $this->paginator->paginate(
            $this->entityManager->getRepository(ContactMessage::class)->findBy([], ['id' => "DESC"]),
            $page,
            $perPage
        );
    }

    public function getElsatica($page, $perPage)
    {
        $query = new Query();
        $query->addSort(['id' => ['order' => 'desc']]);

        return $this->paginator->paginate(
            $this->finder->createPaginatorAdapter($query),
            $page,
            $perPage
        );
    }
}