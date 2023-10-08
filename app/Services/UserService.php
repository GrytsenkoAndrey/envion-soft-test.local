<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class UserService
{
    public function getUsers(): \SimpleXMLElement
    {
        $users = $this->getRandomUsers();

        usort($users, function ($a, $b) {
            return strcmp(strrev($a['last_name']), strrev($b['last_name']));
        });

        $xml = new SimpleXMLElement('<users></users>');

        foreach ($users as $user) {
            $userElement = $xml->addChild('user');
            $userElement->addChild('full_name', $user['full_name']);
            $userElement->addChild('phone', $user['phone']);
            $userElement->addChild('email', $user['email']);
            $userElement->addChild('country', $user['country']);
        }

        return $xml;
    }

    private function getRandomUsers(): array
    {
        $users = [];

        for ($i = 0; $i < 10; $i++) {
            $response = Http::get(config('custom.api.urls.random_user'));
            $data = $response->json();

            if (isset($data['results'][0])) {
                $user = $data['results'][0];
                $full_name = $user['name']['first'] . ' ' . $user['name']['last'];
                $phone = $user['phone'];
                $email = $user['email'];
                $country = $user['location']['country'];

                $users[] = [
                    'full_name' => $full_name,
                    'phone' => $phone,
                    'email' => $email,
                    'country' => $country,
                ];
            }
            usleep(2400);
        }

        return $users;
    }
}
