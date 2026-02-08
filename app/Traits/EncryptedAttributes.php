<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait EncryptedAttributes
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (in_array($key, $this->encrypted ?? [])) {
            try {
                return $value ? Crypt::decryptString($value) : $value;
            } catch (\Exception $e) {
                return $value; // Return original if decryption fails (for migration)
            }
        }
        
        return $value;
    }
    
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypted ?? []) && !empty($value)) {
            $value = Crypt::encryptString($value);
        }
        
        return parent::setAttribute($key, $value);
    }
    
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        
        foreach ($this->encrypted ?? [] as $key) {
            if (isset($attributes[$key])) {
                try {
                    $attributes[$key] = Crypt::decryptString($attributes[$key]);
                } catch (\Exception $e) {
                    // Keep original value if decryption fails
                }
            }
        }
        
        return $attributes;
    }
}
