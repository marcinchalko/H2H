<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContactMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContactMessageRepository::class)]
class ContactMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"IDENTITY")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Imię i nazwisko jest wymagane")]
    #[Groups("ContactMessage")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Adres e-mail jest wymagany")]
    #[Assert\Email(message: "Nieprawidłowy adres e-mail")]
    #[Groups("ContactMessage")]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Treść wiadomości jest wymagana")]
    #[Groups("ContactMessage")]
    private ?string $message = null;

    #[ORM\Column]
    #[Assert\IsTrue(message: "Zgoda na przetwarzanie danych osobowych jest wymagana")]  
    #[Assert\NotBlank(message: "Zgoda na przetwarzanie danych osobowych jest wymagana")]   
    #[Groups("ContactMessage")]
    private ?bool $agreement = null;

    #[ORM\Column]
    #[Groups("ContactMessage")]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isAgreement(): ?bool
    {
        return $this->agreement;
    }

    public function setAgreement(?bool $agreement): static
    {
        $this->agreement = $agreement;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
