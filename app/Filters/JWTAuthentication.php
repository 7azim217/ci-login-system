<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\JWTModel;

class JWTAuthentication implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = null;
        
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
        
        if (!$token) {
            return service('response')->setJSON([
                'status' => 401,
                'error' => true,
                'message' => 'Access token required'
            ])->setStatusCode(401);
        }
        
        try {
            $key = getenv('JWT_SECRET_KEY');
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            
            $jwtModel = new JWTModel();
            if ($jwtModel->isTokenBlacklisted($token)) {
                return service('response')->setJSON([
                    'status' => 401,
                    'error' => true,
                    'message' => 'Token has been invalidated'
                ])->setStatusCode(401);
            }
            
            $request->user = $decoded;
            $request->token = $token;
            
        } catch (\Exception $e) {
            return service('response')->setJSON([
                'status' => 401,
                'error' => true,
                'message' => 'Invalid or expired token'
            ])->setStatusCode(401);
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}