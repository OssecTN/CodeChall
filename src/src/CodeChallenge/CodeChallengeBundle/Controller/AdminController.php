<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CodeChallenge\CodeChallengeBundle\Form\ChallengeAjoutType;
use CodeChallenge\CodeChallengeBundle\Form\ProblemeAjoutType;
use CodeChallenge\CodeChallengeBundle\Form\TestAjoutType;
use CodeChallenge\CodeChallengeBundle\Entity\Challenges;
use CodeChallenge\CodeChallengeBundle\Entity\Tests;
use Symfony\Component\PropertyAccess\PropertyAccess;
use CodeChallenge\CodeChallengeBundle\Entity\Problems;

class AdminController extends Controller {

    public function adminAction() {
	$user = $this->getUser();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->render('CodeChallengeBundle:administration:administration.html.twig', array('user'=>$user));
        }
    }

    public function admin_viewAction() {
	$user = $this->getUser();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Challenges');
            $list = $repository->findBy(array(), array('dateDebut' => 'desc'), 1000, 0);


            return $this->render('CodeChallengeBundle:administration:admin_view.html.twig', array('listchallenges' => $list, 'user'=>$user));
        }
    }

    public function admin_view_challAction($chall) {
	$user = $this->getUser();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Challenges');
            $challenge = $repository->findOneBy(array('nom' => $chall));

            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Joinchall');
            $list = $repository->findBy(array('challenge' => $challenge));

            $accessor = PropertyAccess::createPropertyAccessor();
            //test sur date
            $date_debut = $accessor->getValue($challenge, 'date_debut');
            $time_test = $this->comparedate($date_debut);



            return $this->render('CodeChallengeBundle:administration:admin_view_chall.html.twig', array('listuser' => $list, 'chall' => $chall, 'time' => $time_test, 'user'=>$user));
        }
    }

    public function admin_user_delAction($chall, $user) {
	$usern = $this->getUser();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Challenges');
            $challenge = $repository->findOneBy(array('nom' => $chall));

            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($user);

            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Joinchall');
            $delete = $repository->findOneBy(array('challenge' => $challenge, 'user' => $user));

            $em = $this->getDoctrine()->getManager();
            $em->remove($delete);
            $em->flush();

            $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Joinchall');
            $list = $repository->findBy(array('challenge' => $challenge));

            $accessor = PropertyAccess::createPropertyAccessor();
            //test sur date
            $date_debut = $accessor->getValue($challenge, 'date_debut');
            $time_test = $this->comparedate($date_debut);



            return $this->render('CodeChallengeBundle:administration:admin_view_chall.html.twig', array('listuser' => $list, 'chall' => $chall, 'time' => $time_test, 'user'=>$usern));
        }
    }

    public function admin_view_resultsAction($chall, $user) {
	$usern = $this->getUser();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($user);

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

                if ($codes){
                    foreach ($codes as $code){
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

            return $this->render('CodeChallengeBundle:administration:admin_view_chall_results.html.twig', array('list' => $list_codes, 'useri' => $user, 'chall' => $chall, 'score' => $score, 'user'=>$usern));
        }
    }

    public function challengeAction() {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form1 = $this->createForm(new ChallengeAjoutType());
            if ($this->get('request')->getMethod() == "POST") {
                $form1->bind($this->get('request'));
                $challenge = new Challenges();
                $nom = $form1['nom']->getData();
                $challenge->setnom($nom);
                $duree = $form1['duree']->getData();
                $challenge->setduree($duree);
                $nbrproblems = $form1['nombreProblemes']->getData();
                $challenge->setnbrProblems($nbrproblems);
                $langage = $form1['languages']->getData();
                $challenge->setlangage($langage);
                $dateDebut = $form1['dateDebut']->getData();
                $challenge->setdateDebut($dateDebut);
                $info = $form1['info']->getData();
                $challenge->setinfo($info);
                $this->get('session')->getFlashBag()->add('challenge', $challenge);






                $nbr_problem = $form1['nombreProblemes']->getData();
                //store nbr_problem to pass it to another action
                $this->get('session')->getFlashBag()->add('nbr_problem', $nbr_problem);

                $form2 = $this->createForm(new ProblemeAjoutType());
                $prob = 1;
                return $this->render('CodeChallengeBundle:administration:prob_ajout.html.twig', array('form' => $form2->createView(), 'prob' => $prob, 'user' => $user));
            }

            return $this->render('CodeChallengeBundle:administration:admin.html.twig', array('form' => $form1->createView(), 'user' => $user));
        }
    }

    public function probAction($prob) {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form2 = $this->createForm(new ProblemeAjoutType());
            if ($this->get('request')->getMethod() == "POST") {
                $form2->bind($this->get('request'));
                $problem = new Problems();
                $nom = $form2['nom']->getData();
                $problem->setnom($nom);
                $level = $form2['level']->getData();
                $problem->setlevel($level);
                $nbrtest = $form2['nombreTestes']->getData();
                $problem->setnbrtests($nbrtest);
                $content = $form2['content']->getData();
                $problem->setcontent($content);
                $score = $form2['score']->getData();
                $problem->setscore($score);
                $this->get('session')->getFlashBag()->add('problem', $problem);

                $nbr_test = $form2['nombreTestes']->getData();

                //store nbr_test to pass it to another action
                $this->get('session')->getFlashBag()->add('nbr_test', $nbr_test);


                $form3 = $this->createForm(new TestAjouttype());
                $test = 1;
                return $this->render('CodeChallengeBundle:administration:test_ajout.html.twig', array('form' => $form3->createView(), 'prob' => $prob, 'test' => $test, 'user' => $user));
            }
        }
    }

    public function testAction($prob, $test) {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form3 = $this->createForm(new TestAjoutType());
            if ($this->get('request')->getMethod() == "POST") {
                $form3->bind($this->get('request'));
                $test1 = new Tests();
                $nom = $form3['nom']->getData();
                $test1->setnom($nom);
                $input = $form3['input']->getData();
                $test1->setinput($input);
                $output = $form3['output']->getData();
                $test1->setoutput($output);
                $this->get('session')->getFlashBag()->add('test1', $test1);
                //get nbr_test
                $imp1 = $this->get('session')->getFlashBag()->get('nbr_test');
                $nbr_test = $imp1[0];



                if ($test == $nbr_test) {
                    $form2 = $this->createForm(new ProblemeAjoutType());
                    //get nbr_problem
                    $imp = $this->get('session')->getFlashBag()->get('nbr_problem');
                    $nbr_problem = $imp[0];

                    if ($prob == $nbr_problem) {
                        $imp5 = $this->get('session')->getFlashBag()->get('test1');
                        $this->get('session')->getFlashBag()->add('imp5', $imp5);
                        $em = $this->getDoctrine()->getManager();
                        $imp2 = $this->get('session')->getFlashBag()->get('challenge');
                        $challenge = $imp2[0];
                        $em->persist($challenge);
                        $imp3 = $this->get('session')->getFlashBag()->get('problem');
                        $imp6 = $this->get('session')->getFlashBag()->get('imp5');
                        for ($i = 0; $i < $nbr_problem; $i++) {
                            $imp3[$i]->setChallenge($challenge);
                            $em->persist($imp3[$i]);
                            foreach ($imp6[$i] as $j => $value) {
                                $value->setProblem($imp3[$i]);
                                $em->persist($value);
                            }
                        }
                        $em->flush();
                        return $this->render('CodeChallengeBundle:administration:test.html.twig');
                    }
                    //restore nbr de problem
                    $this->get('session')->getFlashBag()->add('nbr_problem', $nbr_problem);
                    $imp5 = $this->get('session')->getFlashBag()->get('test1');
                    $this->get('session')->getFlashBag()->add('imp5', $imp5);
                    return $this->render('CodeChallengeBundle:administration:prob_ajout.html.twig', array('form' => $form2->createView(), 'prob' => $prob + 1, 'user' => $user));
                } else {
                    //restore nbr de test
                    $this->get('session')->getFlashBag()->add('nbr_test', $nbr_test);
                    return $this->render('CodeChallengeBundle:administration:test_ajout.html.twig', array('form' => $form3->createView(), 'prob' => $prob, 'test' => $test + 1, 'user' => $user));
                }
            }
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
