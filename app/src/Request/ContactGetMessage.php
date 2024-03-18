<?php

namespace App\Request;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ContactGetMessage extends BaseRequest
{
    #[Assert\Positive(message: "Imię i nazwisko jest wymagane")]
    protected int $page;

    #[Assert\Positive(message: "Treść wiadomości jest wymagana")]
    protected int $per_page;
}