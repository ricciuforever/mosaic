<?php

namespace App\Models;

use App\Core\Database;
use Spatie\Permission\Traits\HasRoles;

class User
{
    use HasRoles;

    public int $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public ?string $company_name;
    public ?string $vip_tier;
    public ?string $avatar_initials;
    public bool $is_active;
    public array $attributes = [];

    public function __construct(array $row)
    {
        $this->attributes = $row;
        $this->id = (int)$row['id'];
        $this->name = (string)$row['name'];
        $this->email = (string)$row['email'];
        $this->phone = $row['phone'] ?? null;
        $this->company_name = $row['company_name'] ?? null;
        $this->vip_tier = $row['vip_tier'] ?? null;
        $this->avatar_initials = $row['avatar_initials'] ?? strtoupper(substr($this->name, 0, 2));
        $this->is_active = (bool)($row['is_active'] ?? true);
    }

    public static function find(int $id): ?self
    {
        $row = Database::selectOne("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
        return $row ? new self($row) : null;
    }

    public static function findByEmail(string $email): ?self
    {
        $row = Database::selectOne("SELECT * FROM users WHERE email = ? LIMIT 1", [trim(strtolower($email))]);
        return $row ? new self($row) : null;
    }

    public static function allWithRoles(): array
    {
        $rows = Database::select("SELECT * FROM users ORDER BY id ASC");
        $users = [];
        foreach ($rows as $r) {
            $u = new self($r);
            $users[] = $u;
        }
        return $users;
    }

    public function supplier(): ?array
    {
        return Database::selectOne("SELECT * FROM suppliers WHERE user_id = ? LIMIT 1", [$this->id]);
    }
}
