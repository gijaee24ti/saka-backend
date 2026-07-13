<?php

namespace App\Enums;

enum ProductAvailability: string
{
    case AVAILABLE = 'Tersedia';
    case UNAVAILABLE = 'Tidak Tersedia';
}
