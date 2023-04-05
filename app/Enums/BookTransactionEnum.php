<?php

namespace App\Enums;

enum BookTransactionEnum: string {
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status->value;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}
