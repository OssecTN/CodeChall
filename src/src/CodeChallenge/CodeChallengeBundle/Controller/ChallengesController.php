<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \DateTime;
use \DateInterval;

class ChallengesController extends Controller {

    public function challengesAction() {
        $user = $this->getUser();
       
        if($user){
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $list = $repository->findBy(array(), array('dateDebut' => 'desc'), 1000, 0);

        $accessor = PropertyAccess::createPropertyAccessor();
        $list_chall = array();
        foreach ($list as $challenge) {
            $date_debut = $accessor->getValue($challenge, 'date_debut');
            $duree = $accessor->getValue($challenge, 'duree');
            $time_test = $this->comparedate($date_debut, $duree);
            $challenge->time = $time_test;
            array_push($list_chall, $challenge);
        }

        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Joinchall');
        $join = $repository->findBy(array('user' => $user));

        return $this->render('CodeChallengeBundle:Default:allchallenges.html.twig', array('listchallenges' => $list_chall, 'user' => $user, 'join' => $join));
        }
        else{
            return $this->render('CodeChallengeBundle:Default:notuser.html.twig');
        }
    }

    public function comparedate($date_debut, $duree) {
        $str = $date_debut->format("Y-m-d H:i:s");
        $date_chall = new DateTime($str);
        $now = new DateTime("now");
        $apres = new DateTime($str);
        $apres->add(new DateInterval("PT" . $duree . "H"));

        if ($now < $date_chall) {
            return 0;
        } else if ($date_chall < $now and $now < $apres) {
            return 1;
        } else if ($apres < $now) {
            return 2;
        }
    }

}
