<?php

namespace App\Models;

use App\Core\Database;

class Setting
{
    public static function get(string $key, $default = null)
    {
        try {
            $row = Database::selectOne("SELECT `value` FROM settings WHERE `key` = ? LIMIT 1", [$key]);
            return $row ? $row['value'] : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, string $value, ?string $description = null): void
    {
        $existing = Database::selectOne("SELECT id FROM settings WHERE `key` = ? LIMIT 1", [$key]);
        if ($existing) {
            Database::execute(
                "UPDATE settings SET `value` = ?, updated_at = ? WHERE `key` = ?",
                [$value, date('Y-m-d H:i:s'), $key]
            );
        } else {
            Database::insert('settings', [
                'key' => $key,
                'value' => $value,
                'group_name' => 'system',
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function allKeyed(): array
    {
        $rows = Database::select("SELECT * FROM settings ORDER BY id ASC");
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = $r['value'];
        }
        return $out;
    }
}
