import ImageTool from '@editorjs/image';

/**
 * The image block. It's extended to customize it further.
 */
export default class Image extends ImageTool
{
    constructor({ data, config, api, readOnly }) {
        super({ data, config, api, readOnly });
    }

    renderSettings() {
        return [ ];
    }
}
