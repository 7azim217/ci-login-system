<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\JWTModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    use ResponseTrait;
    
    protected $model;
    protected $jwtModel;
    
    public function __construct()
    {
        $this->model = new UserModel();
        $this->jwtModel = new JWTModel();
    }
    
    public function register()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'messages' => $this->validator->getErrors()
            ], 400);
        }
        
        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password')
        ];
        
        if ($this->model->save($data)) {
            return $this->respondCreated([
                'status' => 201,
                'error' => false,
                'message' => 'User registered successfully'
            ]);
        } else {
            return $this->respond([
                'status' => 500,
                'error' => true,
                'message' => 'Failed to register user'
            ], 500);
        }
    }
    
    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 400,
                'error' => true,
                'messages' => $this->validator->getErrors()
            ], 400);
        }
        
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $user = $this->model->getUserByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->respond([
                'status' => 401,
                'error' => true,
                'message' => 'Invalid email or password'
            ], 401);
        }
        
        $token = $this->generateJWT($user);
        
        return $this->respond([
            'status' => 200,
            'error' => false,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]
            ]
        ]);
    }
    
    public function profile()
    {
        $user = $this->request->user;
        
        return $this->respond([
            'status' => 200,
            'error' => false,
            'data' => [
                'user' => [
                    'id' => $user->uid,
                    'email' => $user->email
                ]
            ]
        ]);
    }
    
    public function logout()
    {
        $token = $this->request->token;
        $userId = $this->request->user->uid;
        $expiry = $this->request->user->exp;
        
        $this->jwtModel->invalidateToken($token, $userId, $expiry);
        
        return $this->respond([
            'status' => 200,
            'error' => false,
            'message' => 'Logged out successfully'
        ]);
    }
    
    protected function generateJWT($user)
    {
        $key = getenv('JWT_SECRET_KEY');
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'uid' => $user['id'],
            'email' => $user['email']
        ];
        
        return \Firebase\JWT\JWT::encode($payload, $key, 'HS256');
    }
}