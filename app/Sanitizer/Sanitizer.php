<?php

namespace App\Sanitizer;

class Sanitizer
{
    public static function handle(string $html, $report=true): string
    {
        // dlog("Sanitizer@handle"); //! LOG

        $builder = new \HtmlSanitizer\SanitizerBuilder();
        $builder->registerExtension(new \App\Sanitizer\BasicExtension());

        $sanitizer = $builder->build([
            'extensions' => ['basic'],
            'tags' => [
                // 'table' => [
                //     'allowed_attributes' => ['class']
                // ],
                'td' => [
                    'allowed_attributes' => ['rowspan', 'colspan']
                ]
            ]
        ]);

        // dlog("INPUT:  $html"); //! LOG

        $safeHtml = $sanitizer->sanitize($html);

        // dlog("OUTPUT: $safeHtml"); //! LOG

        if ($safeHtml != $html && $report) {
            \App\Models\User::informAdmins('Dangerous HTML detected in post description', [
                'description' => $value
            ]);
        }

        return $safeHtml;
    }
}