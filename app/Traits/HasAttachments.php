<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use App\Models\Attachment;

trait HasAttachments
{
    public function addAttachment($attachment, string $group='', $toOrder=false)
    {
        if (!$attachment) {
            return;
        }

        if(is_array($attachment)) {
            $attachments = $attachment;
        } else {
            // if it is 1 item, then we assume that only 1 item possible for this attachmentable, so delete previus attachment
            $attachments = [$attachment];
            Attachment::query()
                ->where('attachmentable_id', $this->id)
                ->where('attachmentable_type', self::class)
                ->where('group', $group)
                ->delete();
        }

        foreach ($attachments as $i => $attachment) {
            
            if (is_string($attachment)) {
                // this attachment already saved - skip
                if ($toOrder) {
                    // update order
                    Attachment::find($attachment)->update([
                        'order' => $i
                    ]);
                }
                continue;
            }

            $type = $this->determineType($attachment->extension());
            $disk = Attachment::disk($type);
            $path = $attachment->store('', $disk);

            Attachment::create([
                'attachmentable_id' => $this->id,
                'attachmentable_type' => self::class,
                'name' => $path,
                'original_name' => $attachment->getClientOriginalName(),
                'type' => $type,
                'group' => $group,
                'order' => $toOrder ? $i : null,
                'size' => $attachment->getSize()
            ]);
        }
    }

    private function determineType($ext)
    {
        if (in_array($ext, ['jpeg','gif','png', 'jpg', 'webp'])) {
            $type = 'image';
        } else if (in_array($ext, ['doc', 'docx', 'pdf'])) {
            $type = 'document';
        } else if (in_array($ext, ['mov','mp4','avi','ogg','wmv','webm','mkv'])) {
            $type = 'video';
        } else {
            $type = 'file';
        }

        return $type;
    }

    public function purgeAttachments()
    {
        $as = Attachment::query()
            ->where('attachmentable_id', $this->id)
            ->where('attachmentable_type', self::class)
            ->get();

        foreach ($as as $a) {
            $a->delete();
        }
    }
}
