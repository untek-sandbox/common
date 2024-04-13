<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\Actions;

class DeleteAction extends BaseAction
{

    public $urlAction = 'delete';
    public $type = 'danger';
    public $title = ['core', 'action.delete'];
    public $icon = 'fa fa-trash';
    public $confirm = ['web', 'message.delete_confirm'];
}
