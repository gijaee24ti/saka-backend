<?php

namespace App\Enums;

enum FeedbackStatus: string
{
    case PENDING = 'Pending';
    case DISPLAYED = 'Ditampilkan';
    case HIDDEN = 'Disembunyikan';
}
