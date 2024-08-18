<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions;

class RestoreAction extends BaseAction
{

    public $urlAction = 'restore';
    public $type = 'success';
    public $title = ['core', 'action.restore'];
    public $icon = 'fas fa-trash-restore';
    public $confirm = ['web', 'message.restore_confirm'];
}
