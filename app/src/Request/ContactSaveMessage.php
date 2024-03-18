<?php

namespace App\Request;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ContactSaveMessage extends BaseRequest
{
    #[Assert\NotBlank(message: "Imię i nazwisko jest wymagane")]
    protected ?string $name = null;

    #[Assert\NotBlank(message: "Adres e-mail jest wymagany")]
    #[Assert\Email(message: "Nieprawidłowy adres e-mail")]
    protected ?string $email = null;

    #[Assert\NotBlank(message: "Treść wiadomości jest wymagana")]
    protected ?string $message = null;

    #[Assert\IsTrue(message: "Zgoda na przetwarzanie danych osobowych jest wymagana")]  
    #[Assert\NotBlank(message: "Zgoda na przetwarzanie danych osobowych jest wymagana")]
    protected ?bool $agreement = null;
}