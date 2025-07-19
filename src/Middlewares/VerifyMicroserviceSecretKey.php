<?php

namespace Corekit\Middlewares;

use Closure;
use Corekit\Traits\HasApiResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class VerifyMicroserviceSecretKey
{
    use HasApiResponse;

    public function handle(Request $request, Closure $next)
    {
        // 1. Récupérer la clé envoyée par le service appelant
        $callerSecretKey = $request->header('Secret-Key');

        if (!$callerSecretKey) {
            return $this->unauthorizedResponse('Secret key is missing');
        }

        // 2. Lire la config via Facades
        $authServiceUrl = Config::get('microservice-auth.auth_service_url');
        $serviceSecretKey = Config::get('microservice-auth.service_secret_key');

        if (!$authServiceUrl || !$serviceSecretKey) {
            return $this->misconfiguredResponse('Auth service URL or service secret key not configured');
        }

        // 3. Appeler le service Auth pour vérifier la clé de l’appelant
        try {
            $client = new Client();

            $response = $client->post($authServiceUrl . '/api/v1/verify-secret-key', [
                'json' => ['secret_key' => $callerSecretKey],
                'headers' => [
                    'X-Service-Secret-Key' => $serviceSecretKey,
                ],
                'timeout' => 2,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (empty($data['valid']) || $data['valid'] !== true) {
                return $this->unauthorizedResponse('Unauthorized: Invalid secret key');
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e, 'Auth service unreachable');
        }

        return $next($request);
    }
}