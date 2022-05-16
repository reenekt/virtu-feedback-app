<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @property int $id
 * @property string $fullname
 * @property string $contact_phone
 * @property string $attachment_file
 * @property string|\DateTimeInterface|null $created_at
 * @property string|\DateTimeInterface|null $updated_at
 */
class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    protected ?UploadedFile $attachmentUploadedFile = null;

    protected $guarded = ['id'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function (self $feedback) {
            if ($feedback->attachmentUploadedFile !== null) {
                // remove old file
                if ($feedback->attachment_file !== null) {
                    $feedback->deleteAttachmentFromStorage();
                }

                $attachmentPath = $feedback->storeAttachmentInStorage();

                if ($attachmentPath) {
                    $feedback->attachment_file = $attachmentPath;
                } else {
                    Log::warning('Feedback attachment was not saved');
                }
            } elseif ($feedback->attachment_file !== null) {
                $feedback->deleteAttachmentFromStorage();
            }
        });

        static::deleting(function (self $feedback) {
            if ($feedback->forceDeleting) {
                $feedback->deleteAttachmentFromStorage();
            }
        });
    }

    public function setAttachmentUploadedFile(?UploadedFile $file): self
    {
        $this->attachmentUploadedFile = $file;

        return $this;
    }

    public function getAttachmentFile(bool $download = false): StreamedResponse
    {
        if ($download) {
            return Storage::disk('local')->download($this->attachment_file);
        }

        return Storage::disk('local')->response($this->attachment_file);
    }

    public function storeAttachmentInStorage(): bool|string
    {
        if ($this->attachmentUploadedFile === null) {
            throw new \Exception('attachmentUploadedFile is null');
        }

        return Storage::disk('local')->putFile(
            'feedback/attachments',
            $this->attachmentUploadedFile
        );
    }

    public function deleteAttachmentFromStorage(): bool
    {
        if ($this->attachment_file !== null) {
            return Storage::disk('local')->delete($this->attachment_file);
        }

        return true;
    }
}
