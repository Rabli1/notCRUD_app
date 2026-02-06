<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Ouvrage extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ouvrages';

    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        '_id',
        'titre',
        'dispo',
        'prix',
        'type',
        'exemplaires',
        'details',
    ];

    protected $casts = [
        'dispo' => 'int',
        'prix' => 'float',
    ];

    public function getDetailsAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value instanceof \MongoDB\Model\BSONDocument) {
            return $value->getArrayCopy();
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return $value ? $value : [];
    }

    public function getExemplairesAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value instanceof \MongoDB\Model\BSONArray) {
            return $value->getArrayCopy();
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return $value ? $value : [];
    }
}
