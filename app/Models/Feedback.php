<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $fullname
 * @property string $contact_phone
 * @property string|\DateTimeInterface|null $created_at
 * @property string|\DateTimeInterface|null $updated_at
 */
class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
}
