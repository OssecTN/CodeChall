<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        $user = $this->getUser();
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $list = $repository->findBy(array(), array('dateDebut' => 'desc'), 2, 0);
        
         $repository1 = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $challenge = $repository1->findOneBy(array('nom' => 'Challenge5'));
        
        $repo = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Joinchall');

        $qb = $repo->createQueryBuilder('a');
        $qb->select('COUNT(a)');
        $qb->where('a.challenge = :challenge');
        $qb->setParameter('challenge', $challenge);

        $count = $qb->getQuery()->getSingleScalarResult();
        
        $userManager = $this->container->get('fos_user.user_manager');
        $user_m = $userManager->findUsers();
        $count_u = 0;
        foreach($user_m as $u)
            $count_u ++;

        return $this->render('CodeChallengeBundle:Default:index.html.twig', array('listchallenges' => $list, 'user' => $user, 'count_j' => $count, 'count_u'=> $count_u));
    }

    public function lessonsAction() {
        $user = $this->getUser();
        return $this->render('CodeChallengeBundle:Default:lessons.html.twig', array('user' => $user));
    }
    

}
