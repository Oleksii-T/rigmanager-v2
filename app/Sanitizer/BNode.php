<?php

namespace App\Sanitizer;

use HtmlSanitizer\Node\AbstractTagNode;
use HtmlSanitizer\Node\HasChildrenTrait;

class BNode extends AbstractTagNode
{
    use HasChildrenTrait; // Or IsChildlessTrait

    public function getTagName(): string
    {
        return 'b';
    }
}