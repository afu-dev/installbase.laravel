<?php

namespace App\Enums;

enum Vendor: string
{
    case BITSIGHT = 'bitsight';
    case CENSYS = 'censys';
    case SHODAN = 'shodan';
}
