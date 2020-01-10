<?php

namespace App;

class FieldType
{
    const BOOLEAN = 'boolean';
    const STRING = 'string';
    const NUMBER = 'number';
    const DATE = 'date';

    const ALLOWED_TYPES = [
        self::BOOLEAN,
        self::DATE,
        self::STRING,
        self::NUMBER,
    ];
}
