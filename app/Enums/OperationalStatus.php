<?php

namespace App\Enums;

enum OperationalStatus: string
{
    case SELLING = 'Berjualan';
    case BREAK = 'Istirahat';
    case CLOSED = 'Tutup';
    case MOVING = 'Pindah';
}
