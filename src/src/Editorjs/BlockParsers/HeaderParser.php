<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class HeaderParser implements BlockParser
{
    /** {@inheritdoc} */
    public static function getBlockType(): string
    {
        return 'header';
    }

    /** {@inheritdoc} */
    public function parse(stdClass $blockData): string
    {
        // Заголовком по умолчанию ставится H2 т.к. это наиболее ожидаемый результат.
        // По правилам семантики заголовок H1 на странице должнен быть только один.
        $level = $blockData->level ?: 2;
        $headerTag = "h$level";

        return <<<"END"
                <$headerTag>$blockData->text</$headerTag>
            END;
    }
}
