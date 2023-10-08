<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RandomUserControllerTest extends TestCase
{
    public function testRandomUserController()
    {
        Http::fake([
            config('custom.api.urls.random_user') => Http::response($this->getMockedUserResponse(), 200),
        ]);

        $response = $this->get('/users');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml');

        $xml = new SimpleXMLElement($response->getContent());
        $users = [];

        foreach ($xml->user as $user) {
            $users[] = [
                'full_name' => (string)$user->full_name,
                'phone' => (string)$user->phone,
                'email' => (string)$user->email,
                'country' => (string)$user->country,
            ];
        }

        $expectedUsers = $this->getExpectedUsers();
        $this->assertEquals($expectedUsers, $users);
    }

    private function getMockedUserResponse(): array
    {
        return [
            'results' => [
                [
                    'name' => [
                        'first' => 'John',
                        'last' => 'Doe',
                    ],
                    'phone' => '1234567890',
                    'email' => 'johndoe@example.com',
                    'location' => [
                        'country' => 'United States',
                    ]
                ]
            ]
        ];
    }

    private function getExpectedUsers(): array
    {
        return [
            [
                'full_name' => 'John Doe',
                'phone' => '1234567890',
                'email' => 'johndoe@example.com',
                'country' => 'United States',
            ]
        ];
    }
}
