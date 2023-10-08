<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function __invoke()
    {
        try {
            $xml = $this->userService->getUsers();

            return response($xml->asXML(), 200)
                ->header('Content-Type', 'application/xml');
        } catch (\Exception $e) {
            Log::error('Request was failed. Error: ' . $e->getMessage());

            return response()->json('Failed request', Response::HTTP_BAD_REQUEST);
        }
    }
}
