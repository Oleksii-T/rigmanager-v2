<?php

namespace App\Sanitizer;

use App\Sanitizer\BNode;
use HtmlSanitizer\Model\Cursor;
use HtmlSanitizer\Node\NodeInterface;
use HtmlSanitizer\Visitor\AbstractNodeVisitor;
use HtmlSanitizer\Visitor\HasChildrenNodeVisitorTrait;
use HtmlSanitizer\Visitor\NamedNodeVisitorInterface;

class BNodeVisitor extends AbstractNodeVisitor implements NamedNodeVisitorInterface
{
    use HasChildrenNodeVisitorTrait; // Or IsChildlessTagVisitorTrait

    protected function getDomNodeName(): string
    {
        return 'b';
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
        $node = new BNode($cursor->node);
        
        // You can use $this->config['custom_config'] to access the user-defined configuration

        return $node;
    }
}