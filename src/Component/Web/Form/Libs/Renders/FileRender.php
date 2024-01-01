<?php

namespace Untek\Component\Web\Form\Libs\Renders;

class FileRender extends BaseInputRender
{

    public function render(): string
    {
        $options = $this->options();
        return '
            <div class="custom-file">
                <input type="file" name="' . $options['name'] . '" id="entity-imagefile" class="custom-file-input file-uploader">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>';
    }
}
