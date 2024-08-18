<?php

namespace Untek\Component\Web\Form\Interfaces;

use Symfony\Component\Form\FormBuilderInterface;

interface BuildFormInterface
{

    public function buildForm(FormBuilderInterface $formBuilder);
}
