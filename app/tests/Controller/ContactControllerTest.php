<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends WebTestCase
{
    
    public function testSaveFirstMessage(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Test message',
                'agreement' => true,
            ])
        );

        $this->assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithInvalidName(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => '',
                'email' => 'john@example.com',
                'message' => 'Test message',
                'agreement' => true,
            ])
        );
        
        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithInvalidEmail(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'invalid_email',
                'message' => 'Test message',
                'agreement' => true,
            ])
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithInvalidMessage(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => '',
                'agreement' => true,
            ])
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithInvalidAgreement(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Test message',
                'agreement' => false,
            ])
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithNullAgreement(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Test message',
                'agreement' => null,
            ])
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithEmptyPayload(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([])
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testSaveMessageWithPartialPayload(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ])
        );

        $this->assertSame(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }


    public function testGetMessages(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/contactMessage');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('more', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('name', $responseData['data'][0]);
        $this->assertArrayHasKey('email', $responseData['data'][0]);
        $this->assertArrayHasKey('message', $responseData['data'][0]);
        $this->assertArrayHasKey('agreement', $responseData['data'][0]);
        $this->assertEquals('John Doe', $responseData['data'][0]['name']);
        $this->assertEquals('john@example.com', $responseData['data'][0]['email']);
        $this->assertEquals('Test message', $responseData['data'][0]['message']);
        $this->assertTrue($responseData['data'][0]['agreement']);

    }

    public function testSaveSecondMessage(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/contactMessage',
            [],
            [],
            [],
            json_encode([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Test message',
                'agreement' => true,
            ])
        );

        $this->assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testGetMessagesFirstPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/contactMessage?page=1&per_page=1');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('more', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('name', $responseData['data'][0]);
        $this->assertArrayHasKey('email', $responseData['data'][0]);
        $this->assertArrayHasKey('message', $responseData['data'][0]);
        $this->assertArrayHasKey('agreement', $responseData['data'][0]);
        $this->assertEquals('John Doe', $responseData['data'][0]['name']);
        $this->assertEquals('john@example.com', $responseData['data'][0]['email']);
        $this->assertEquals('Test message', $responseData['data'][0]['message']);
        $this->assertTrue($responseData['data'][0]['agreement']);

    }

    public function testGetMessagesSecondPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/contactMessage?page=2&per_page=1');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('more', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('name', $responseData['data'][0]);
        $this->assertArrayHasKey('email', $responseData['data'][0]);
        $this->assertArrayHasKey('message', $responseData['data'][0]);
        $this->assertArrayHasKey('agreement', $responseData['data'][0]);
        $this->assertEquals('John Doe', $responseData['data'][0]['name']);
        $this->assertEquals('john@example.com', $responseData['data'][0]['email']);
        $this->assertEquals('Test message', $responseData['data'][0]['message']);
        $this->assertTrue($responseData['data'][0]['agreement']);

    }
}
