<?php

namespace CF\MarsubookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MarsuType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
  $builder
  ->add('sexe', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
    'choices'  => array(
      'Mâle' => "M",
      'Femelle' => "F",
    ),
  ))
  ->add('prenom', TextType::class)
  ->add('naissance', BirthdayType::class)
  ->add('taille', NumberType::class)
  ->add('nourriture', TextType::class)
  ->add('save',      SubmitType::class)
  ;

}

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'CF\MarsubookBundle\Entity\Marsu'
      ));
    }
  }
