<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;

use CodeChallenge\CodeChallengeBundle\Entity\Joinchall;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TimeController extends Controller {

    public function timeAction($chall) {
        $user = $this->getUser();
        if($user){
        //join function
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $challenge = $repository->findOneBy(array('nom' => $chall));
        $accessor = PropertyAccess::createPropertyAccessor();
        $date_debut = $accessor->getValue($challenge, 'date_debut');
        $time_test = $this->comparedate($date_debut);
        if ($time_test == 0) {
            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Joinchall');
            $join = $repository->findOneBy(array('user' => $user, 'challenge' => $challenge));
            if (!$join) {
                $datenow = date('Y-m-d H:i:s');
                $em = $this->getDoctrine()->getManager();
                $join = new Joinchall();
                $join->setdate(new \DateTime("$datenow"));
                $join->setChallenge($challenge);
                $join->setUser($this->getUser());
                $em->persist($join);
                $em->flush();
            }
        }



        //time controller
        $repository1 = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $challenge = $repository1->findOneBy(array('nom' => $chall));
        $accessor = PropertyAccess::createPropertyAccessor();
        $date_debut = $accessor->getValue($challenge, 'date_debut');

        return $this->render('CodeChallengeBundle:Default:time.html.twig', array('user' => $user, 'date' => $date_debut, 'chall' => $chall));
      }
        else{
            return $this->render('CodeChallengeBundle:Default:notuser.html.twig');
        }
    }

    public function comparedate($date_debut) {
        $date_deb = $date_debut->format('m/d/Y');
        $time_deb = $date_debut->format('H:i:s');
        $date = date('m/d/Y');
        $time = date('H:i:s');
        if ($date < $date_deb) {
            return 0;
        } // pas encore
        else if ($date == $date_deb and $time < $time_deb) {
            return 0;
        } else {
            return 1;
        }
    }

}
