<?php

namespace App\Sanitizer;

use App\Sanitizer\StrikeNode;
use HtmlSanitizer\Model\Cursor;
use HtmlSanitizer\Node\NodeInterface;
use HtmlSanitizer\Visitor\AbstractNodeVisitor;
use HtmlSanitizer\Visitor\HasChildrenNodeVisitorTrait;
use HtmlSanitizer\Visitor\NamedNodeVisitorInterface;

class StrongNodeVisitor extends AbstractNodeVisitor implements NamedNodeVisitorInterface
{
    use HasChildrenNodeVisitorTrait; // Or IsChildlessTagVisitorTrait

    protected function getDomNodeName(): string
    {
        return 'strong';
    }

    public function getDefaultAllowedAttributes(): array
    {
        return [
            
        ];
    }

    public function getDefaultConfiguration(): array
    {
        return [
            'custom_config' => null,
        ];
    }

    protected function createNode(\DOMNode $domNode, Cursor $cursor): NodeInterface
    {
        // You need to pass the current node as your node parent
        $node = new StrongNode($cursor->node);
        
        // You can use $this->config['custom_config'] to access the user-defined configuration

        return $node;
    }
}