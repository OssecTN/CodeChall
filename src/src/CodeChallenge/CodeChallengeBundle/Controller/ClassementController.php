<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ClassementController extends Controller {

    public function classementAction($chall) {
    
        $user = $this->getUser();
        if($user){
        $repository1 = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $challenge = $repository1->findOneBy(array('nom' => $chall));

        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Score');
        $list = $repository->findBy(array('challenge' => $challenge), array('score' => 'desc', 'time_final' => 'asc'), 1000, 0);
        
        return $this->render('CodeChallengeBundle:Default:classement.html.twig', array('list' => $list, 'user' => $user));
     }
        else{
            return $this->render('CodeChallengeBundle:Default:notuser.html.twig');
        }
    }
    

}
