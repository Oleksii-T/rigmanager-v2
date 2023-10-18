<?php

namespace App\Services;

class ProcessImageService
{
    public static function resize($w, $h, $path)
    {
        // dlog("ProcessImageService@resize. w $w | h $h | path $path"); //! LOG
        $out = self::getCompressedName($w, $h, $path);
        $intervention = \Image::make($path);

        if ($intervention->width() <= $w && $intervention->height() <= $h) {
            // do not resize
            return $path;
        }

        $intervention->resize($w, $h, function ($constraint) {
            $constraint->aspectRatio();
        });


        $intervention->save($out);

        // dlog(" ProcessImageService@resize. result: $out"); //! LOG

        return $out;

        //? save compressed image as separate Attachment
    }

    public static function watermark($path)
    {
        // dlog("ProcessImageService@watermark. path: $path"); //! LOG
        $intervention = \Image::make($path); // initialize intervention
        $w = $intervention->width();
        $w *= 0.3;

        // get closest number divisible by 50
        $rounded = round($w / 50) * 50;
        $rounded = ($w - $rounded <= 25) ? $rounded : $rounded + 50;
        $rounded = $rounded ? $rounded : 20;

        $ogWMPath = public_path('icons/watermarks/watermark.png');
        $neededWMPath = public_path("icons/watermarks/watermark-$rounded.png");

        if (!file_exists($neededWMPath)) {
            $interventionWM = \Image::make($ogWMPath);
            $interventionWM->resize($rounded, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($neededWMPath);
        }

        $intervention->insert($neededWMPath, 'bottom-left', 10, 10)->save($path);
    }

    public static function convert($path, $ext='webp')
    {
        // dlog("ProcessImageService@convert. path: $path"); //! LOG
        if (self::getExt($path) == $ext) {
            return $path;
        }

        $oldPath = $path; // remember old path
        $newPath = self::changeExt($path, $ext);
        $intervention = \Image::make($oldPath); // initialize intervention
        $intervention->encode($ext, 100); // convern to .webp image
        $intervention->save($newPath); // save converted image
        unlink($oldPath); // remove old image

        return $newPath;
    }

    /**
     * Convert path or url to file with compressed verion.
     *
     * @param number $w   Width of compressed image
     * @param number $h   Height of compressed image
     * @param string $val Path or url to original image. Example: https://domain/storage/attachments/images/imagename.jpg
     *
     * @return string     Path or url to compressed image. Example: https://domain/storage/attachments/images/compressed/imagename-250-250.jpg
     */
    public static function getCompressedName($w, $h, $val)
    {
        $segments = explode('/', $val);
        $segments[count($segments)-1] = "compressed/" . $segments[count($segments)-1];
        $val = implode('/', $segments);
        $path = explode('.', $val);
        $i = count($path)-2;
        $path[$i] = $path[$i] . "-$w-$h";
        $path = implode('.', $path);

        return $path;
    }

    public static function getExt($path)
    {
        $path = explode('.', $path);

        return $path[count($path)-1];
    }

    public static function changeExt($path, $ext='webp')
    {
        $path = explode('.', $path);
        $path[count($path)-1] = $ext;
        $path = implode('.', $path);

        return $path;
    }

    public static function mimeFromUrl($url)
    {
        try {
            $mime = exif_imagetype($url);
            $mimes  = [
                IMAGETYPE_GIF => "gif",
                IMAGETYPE_JPEG => "jpg",
                IMAGETYPE_PNG => "png",
                IMAGETYPE_WEBP => "webp",
                IMAGETYPE_BMP => "bmp",
                // IMAGETYPE_SWF => "image/swf",
                // IMAGETYPE_PSD => "image/psd",
                // IMAGETYPE_JPC => "image/jpc",
                // IMAGETYPE_JP2 => "image/jp2",
                // IMAGETYPE_JPX => "image/jpx",
                // IMAGETYPE_JB2 => "image/jb2",
                // IMAGETYPE_SWC => "image/swc",
                // IMAGETYPE_IFF => "image/iff",
                // IMAGETYPE_WBMP => "image/wbmp",
                // IMAGETYPE_XBM => "image/xbm",
                // IMAGETYPE_ICO => "image/ico",
            ];

            $mime = $mimes[$mime];
        } catch (\Throwable $th) {
            $mime = null;
        }

        return $mime;
    }
}
