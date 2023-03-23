<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

/**
 * The interface of a block parsers. This service parses a block from Editorjs to the HTML code.
 */
interface BlockParser
{
    public static function getBlockType(): string;

    public function parse(stdClass $blockData): string;
}
