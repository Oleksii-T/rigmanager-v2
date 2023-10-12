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

        $isMultiple = false;

        if(is_array($attachment)) {
            $isMultiple = true;
            $attachments = $attachment;
        } else {
            // if it is 1 item, then we assume that only 1 item possible for this attachmentable, so delete previus attachment
            $attachments = [$attachment];
            dlog(" add one attachment $group"); //! LOG
            $toDel = Attachment::query()
                ->where('attachmentable_id', $this->id)
                ->where('attachmentable_type', self::class)
                ->where('group', $group)
                ->get();
            foreach ($toDel as $td) {
                $td->delete();
            }
        }

        $result = [];

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

            $result[] = Attachment::create([
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

        if ($isMultiple) {
            return $result;
        }

        return $result[0]??null;
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
