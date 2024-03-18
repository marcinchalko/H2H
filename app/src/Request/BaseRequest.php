<?php

namespace App\Request;

use App\Exception\InvalidJsonRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    public function __construct(
        protected ValidatorInterface $validator, 
        protected RequestStack $requestStack
    ) {
        $this->populate();
        $this->validate();
    }

    public function validate()
    {
        $errors = $this->validator->validate($this);

        $messages = ['message' => 'validation_failed', 'errors' => []];

        if (! empty($errors)) {
            foreach ($errors as $message) {
                $messages['errors'][] = [
                    'property' => $message->getPropertyPath(),
                    'value' => $message->getInvalidValue(),
                    'message' => $message->getMessage(),
                ];
            }
        }

        if (! empty($messages['errors'])) {
            throw new InvalidJsonRequest( $messages['errors']);
        }
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getContent(): array
    {
        return json_decode($this->getRequest()->getContent(), true);
    }

    public function getQuery(): array
    {
        parse_str($this->getRequest()->getQueryString(), $queryArray);

        return  $queryArray;
    }

    protected function populate(): void    
    {
        if (! empty($this->getRequest()->getQueryString())) {
            parse_str($this->getRequest()->getQueryString(), $queryArray);        
            foreach ($queryArray as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->{$property} = $value;
                }
            }
        }
        if (! empty($this->getRequest()->getContent())) {
            foreach ($this->getRequest()->toArray() as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->{$property} = $value;
                }
            }
        }
    }
}