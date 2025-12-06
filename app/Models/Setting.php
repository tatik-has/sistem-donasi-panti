<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;
    
    // PENTING: Primary Key adalah 'key' (bukan 'id')
    protected $primaryKey = 'key';
    public $incrementing = false; 
    protected $keyType = 'string';
    
    protected $fillable = ['key', 'value'];
    public $timestamps = false; 

    /**
     * Helper: Ambil nilai pengaturan (dengan caching)
     */
    public static function getValue($key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function() use ($key, $default) {
            $setting = self::find($key);
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Helper: Simpan/update nilai dan hapus cache
     */
    public static function setValue($key, $value)
    {
        $setting = self::find($key);

        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            self::create(['key' => $key, 'value' => $value]);
        }
        
        Cache::forget("settings.{$key}");
    }
    
    /**
     * Helper: Ambil SEMUA pengaturan sebagai array
     */
    public static function getAll()
    {
        return Cache::rememberForever('settings.all', function() {
            return self::pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Helper: Hapus semua cache settings
     */
    public static function clearCache()
    {
        Cache::forget('settings.all');
        $keys = ['nama_panti', 'rekening_bank', 'email_kontak', 'whatsapp_number', 'footer_text'];
        foreach ($keys as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}