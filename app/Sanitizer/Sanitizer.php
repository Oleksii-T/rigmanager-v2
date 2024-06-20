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
            'max_input_length' => 9000000,
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
                'description' => $safeHtml
            ]);
        }

        $html = $safeHtml;

        $html = str_replace('<p></p>', '', $html);
        $html = str_replace('<p><br></p>', '', $html);
        $html = str_replace('<p><br /></p>', '', $html);
        $html = str_replace("<p>\u{A0}</p>", '', $html);
        $html = str_replace('<br></p>', '</p>', $html);
        $html = str_replace('<br /></p>', '</p>', $html);
        $html = str_replace('<p><strong></strong></p>', '', $html);
        
        // dd($html);

        if (str_contains($html, '</table>')) {
            $dom = new \DOMDocument;
            $html = str_replace('&nbsp;', '', $html);
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            
            $xpath = new \DOMXPath($dom);

            $tds = $xpath->query('//td');

            foreach ($tds as $td) {
                // Remove 'br' elements
                $brs = $td->getElementsByTagName('br');
                for ($i = $brs->length - 1; $i >= 0; $i--) {
                    $brs->item($i)->parentNode->removeChild($brs->item($i));
                }
                // Unwrap 'p' elements
                foreach ($td->getElementsByTagName('p') as $p) {
                    while ($p->childNodes->length > 0) {
                        $p->parentNode->insertBefore($p->childNodes->item(0), $p);
                    }
                    $p->parentNode->removeChild($p);
                }
            }

            $html = $dom->saveHTML();
            $html = str_replace('<?xml encoding="utf-8" ?>', '', $html);
        }

        return $html;
    }
}