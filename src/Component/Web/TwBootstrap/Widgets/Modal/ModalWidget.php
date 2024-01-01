<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Modal;

use Untek\Component\Web\Html\Helpers\Html;
use Untek\Component\Web\Widget\Base\BaseWidget2;

class ModalWidget extends BaseWidget2
{

    public $tagId;
    public $header;
    public $body;
    public $bodyOptions = [
        'class' => 'modal-body',
    ];
    public $footer;
    public $centered = false;

    public function run(): string
    {
        return '
            <div class="modal fade" id="' . $this->tagId . '" tabindex="-1" role="dialog" aria-labelledby="' . $this->tagId . 'Label" aria-hidden="true">
                <div class="' . $this->generateDialogCssClass() . '" role="document">
                    <div class="modal-content">
                        ' . $this->generateHeaderHtml() . '
                        ' . $this->generateBodyHtml() . '
                        ' . $this->generateFooterHtml() . '
                    </div>
                </div>
            </div>
        ';
    }

    protected function generateDialogCssClass(): string
    {
        $dialogCssClass = 'modal-dialog';
        if ($this->centered) {
            $dialogCssClass .= ' modal-dialog-centered';
        }
        return $dialogCssClass;
    }

    protected function generateCloseButtonHtml(): string
    {
        return '
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>';
    }

    protected function generateHeaderHtml(): string
    {
        if(empty($this->header)) {
            return '';
        }
        return '
            <div class="modal-header">
                <h5 class="modal-title" id="' . $this->tagId . 'Label">
                    ' . $this->header . '
                </h5>
                '. $this->generateCloseButtonHtml() .'
            </div>';
    }

    protected function generateBodyHtml(): string
    {
        if(empty($this->body)) {
            return '';
        }
        return Html::tag('div', $this->body, $this->bodyOptions);
        /*return '
            <div class="modal-body">
                ' . $this->body . '
            </div>
        ';*/
    }

    protected function generateFooterHtml(): string
    {
        if(empty($this->footer)) {
            return '';
        }
        return '
            <div class="modal-footer">
                ' . $this->footer . '
            </div>
        ';
    }
}