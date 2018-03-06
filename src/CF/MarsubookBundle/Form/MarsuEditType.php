<?php

namespace CF\MarsubookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MarsuEditType extends AbstractType
{

  public function getParent()
  {
    return MarsuType::class;
  }
}
