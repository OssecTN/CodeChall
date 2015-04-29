<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;
use CodeChallenge\CodeChallengeBundle\Entity\Problems;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ResultsController extends Controller {

    public function resultAction($chall) {
        $user = $this->getUser();
        if($user){
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $challenge = $repository->findOneBy(array('nom' => $chall));

        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Problems');
        $list_problems = $repository->findBy(array('challenge' => $challenge));
        
        $list_codes = array();
        foreach ($list_problems as $problem) {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Codes');
           $codes = $repository->findBy(array('problem' => $problem, 'user' => $user));
           
           if($codes){
                foreach ($codes as $code) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $nom_problem = $accessor->getValue($problem, 'nom');
                $code->prob = $nom_problem;
                array_push($list_codes, $code);
                }
           }
        }
        
         $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Score');
         $score = $repository->findOneBy(array('challenge' => $challenge, 'user' => $user));
      
        return $this->render('CodeChallengeBundle:Default:results.html.twig', array('list' => $list_codes, 'user' => $user, 'chall' => $chall, 'score' => $score));
      }
        else{
            return $this->render('CodeChallengeBundle:Default:notuser.html.twig');
        }
    
    }
}
