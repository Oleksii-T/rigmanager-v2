<?php

namespace App\Sanitizer;

use HtmlSanitizer\Extension\ExtensionInterface;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @final
 */
class BasicExtension implements ExtensionInterface
{
    public function getName(): string
    {
        return 'basic';
    }

    public function createNodeVisitors(array $config = []): array
    {
        return [
            // basic
            'br' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\BrNodeVisitor($config['tags']['br'] ?? []),
            'h2' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\H2NodeVisitor($config['tags']['h2'] ?? []),
            'h3' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\H3NodeVisitor($config['tags']['h3'] ?? []),
            'h4' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\H4NodeVisitor($config['tags']['h4'] ?? []),
            'i' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\INodeVisitor($config['tags']['i'] ?? []),
            'p' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\PNodeVisitor($config['tags']['p'] ?? []),
            'u' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\UNodeVisitor($config['tags']['u'] ?? []),
            'sub' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\SubNodeVisitor($config['tags']['sub'] ?? []),
            'sup' => new \HtmlSanitizer\Extension\Basic\NodeVisitor\SupNodeVisitor($config['tags']['sup'] ?? []),

            //table
            'table' => new \HtmlSanitizer\Extension\Table\NodeVisitor\TableNodeVisitor($config['tags']['table'] ?? []),
            'tbody' => new \HtmlSanitizer\Extension\Table\NodeVisitor\TbodyNodeVisitor($config['tags']['tbody'] ?? []),
            'td' => new \HtmlSanitizer\Extension\Table\NodeVisitor\TdNodeVisitor($config['tags']['td'] ?? []),
            'tfoot' => new \HtmlSanitizer\Extension\Table\NodeVisitor\TfootNodeVisitor($config['tags']['tfoot'] ?? []),
            'thead' => new \HtmlSanitizer\Extension\Table\NodeVisitor\TheadNodeVisitor($config['tags']['thead'] ?? []),
            'th' => new \HtmlSanitizer\Extension\Table\NodeVisitor\ThNodeVisitor($config['tags']['th'] ?? []),
            'tr' => new \HtmlSanitizer\Extension\Table\NodeVisitor\TrNodeVisitor($config['tags']['tr'] ?? []),

            // list
            'li' => new \HtmlSanitizer\Extension\Listing\NodeVisitor\LiNodeVisitor($config['tags']['li'] ?? []),
            'ol' => new \HtmlSanitizer\Extension\Listing\NodeVisitor\OlNodeVisitor($config['tags']['ol'] ?? []),
            'ul' => new \HtmlSanitizer\Extension\Listing\NodeVisitor\UlNodeVisitor($config['tags']['ul'] ?? []),

            // custom
            'b' => new \App\Sanitizer\BNodeVisitor($config['tags']['b'] ?? []),
            'strong' => new \App\Sanitizer\StrongNodeVisitor($config['tags']['strong'] ?? []),
            'strike' => new \App\Sanitizer\StrikeNodeVisitor($config['tags']['strike'] ?? []),
        ];
    }
}
