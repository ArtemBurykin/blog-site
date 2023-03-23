<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class ParagraphParser implements BlockParser
{
    /** {@inheritdoc} */
    public static function getBlockType(): string
    {
        return 'paragraph';
    }

    /** {@inheritdoc} */
    public function parse(stdClass $blockData): string
    {
        return <<<"END"
            <p>$blockData->text</p>
            END;
    }
}
