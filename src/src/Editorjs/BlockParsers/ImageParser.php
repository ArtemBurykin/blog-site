<?php

namespace App\Editorjs\BlockParsers;

use stdClass;

class ImageParser implements BlockParser
{
    /**
     * @inheritDoc
     */
    public static function getBlockType(): string
    {
        return 'image';
    }

    /**
     * @inheritDoc
     */
    public function parse(stdClass $blockData): string
    {
        $src = $blockData->file->url;
        $alt = htmlspecialchars($blockData->caption);

        return <<< "END"
                <div class="content-image">
                    <img src="$src" alt="$alt" />
                </div>
            END;
    }
}
