<?php

namespace CodeChallenge\CodeChallengeBundle\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CodeChallenge\CodeChallengeBundle\Form\challengeform;

class ChallengeformController extends Controller
{
    public function challengeformAction() {
       
        $form = $this->createForm(new challengeform());
        
        return $this->render('CodeChallengeBundle:administration:challengeform.html.twig', array('form' => $form->createView()));
        die('ici'); 
        
    }
    
    
    
    
    
    
}