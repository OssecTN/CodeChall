<?php

namespace CodeChallenge\CodeChallengeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class challengeform extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //ici les formulaires
        $builder->add('test','textarea');
    }
    
    public function getName()
    {
        return 'codechallenge_codechallengebundle_challengeform';
    }
}


