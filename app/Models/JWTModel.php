<?php

namespace App\Models;

use CodeIgniter\Model;

class JWTModel extends Model
{
    protected $table = 'jwt_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['token', 'user_id', 'expires_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    
    public function isTokenBlacklisted($token)
    {
        return $this->where('token', $token)->first();
    }
    
    public function invalidateToken($token, $userId, $expiry)
    {
        return $this->insert([
            'token' => $token,
            'user_id' => $userId,
            'expires_at' => date('Y-m-d H:i:s', $expiry)
        ]);
    }
}