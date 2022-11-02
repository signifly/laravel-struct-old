<?php

namespace Signifly\Struct;

class Factory
{
    public static function fromConfig(): Struct
    {
        return new Struct(
            config('struct.credentials.api_key'),
            config('struct.credentials.base_uri'),
        );
    }

    public static function fromArray(array $data): Struct
    {
        return new Struct(
            $data['api_key'],
            $data['base_url']
        );
    }
}
