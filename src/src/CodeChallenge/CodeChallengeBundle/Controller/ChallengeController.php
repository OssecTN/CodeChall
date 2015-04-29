<?php

namespace CodeChallenge\CodeChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CodeChallenge\CodeChallengeBundle\Entity\Codes;
use CodeChallenge\CodeChallengeBundle\Entity\Score;
use CodeChallenge\CodeChallengeBundle\Entity\Tests;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SoapClient;
use DateTime;
use DateInterval;

class ChallengeController extends Controller {

    public function challengeAction($prob, $chall, $lang) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $repository1 = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Challenges');
        $challenge = $repository1->findOneBy(array('nom' => $chall));

        //si user dans Joinchallenge table
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('CodeChallengeBundle:Joinchall');
        $join = $repository->findOneBy(array('user' => $user, 'challenge' => $challenge));
        if ($join) {


            $accessor = PropertyAccess::createPropertyAccessor();
            //test sur date
            $date_debut = $accessor->getValue($challenge, 'date_debut');
            $duree = $accessor->getValue($challenge, 'duree');

            $time_test = $this->comparedate($date_debut, $duree);
            if ($time_test == 0) {
                return $this->redirect($this->generateUrl('attente', array('date_debut' => $date_debut, 'chall' => $chall)));
            }
            if ($time_test == 2) {
                return $this->redirect($this->generateUrl('classement', array('chall' => $chall)));
            }


            //nbr problemes
            $nbr_prob = $accessor->getValue($challenge, 'nbr_problems');
            //ennoncé du probleme 

            $repository2 = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Problems');
            $problem = $repository2->findOneBy(array('nom' => $prob, 'challenge' => $challenge));

            $nbr_tests = $accessor->getValue($problem, 'nbr_tests');

            //code
            $repository3 = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Codes');
            $code = $repository3->findOneBy(array('user' => $this->getUser(), 'problem' => $problem, 'langage' => $lang, 'user' => $this->getUser()));
            //score
            $repository4 = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Score');
            $scor = $repository4->findOneBy(array('user' => $this->getUser(), 'challenge' => $challenge));

            //date - time now
            $datenow = date('Y-m-d H:i:s');
            
            $result = array();
            $out = array();
            $score = 0;

            if ($this->get('request')->getMethod() == 'POST') {
                $request = $this->getRequest();
                $postData = $request->request->get('code');
                for ($i = 1; $i < $nbr_tests + 1; $i++) {
                    $nom = "test" . $i;

                    //input et output
                    $repository3 = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('CodeChallengeBundle:Tests');
                    $test = $repository3->findOneBy(array('problem' => $problem, 'nom' => $nom));
                    $input = $accessor->getValue($test, 'input');
                    $output = $accessor->getValue($test, 'output');
                    
                    
                    $out[$i-1] = $this->compile($postData, $input, $lang);
                    if ($out[$i - 1]) {
                        if ($out[$i - 1] === $output) {
                            $result[$i - 1] = "True";
                            $score = 100;
                            $err = 0;
                        } else {
                            $score = 0;
                            $err = 1; // erruer de compilation
                            $result[$i - 1] = "False";
                        }
                    } else{
                        $result[$i - 1] = "Compilation error";
                        $score = 0;
                        $err = 1; // erruer de compilation
                    }
                }

                 $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CodeChallengeBundle:Codes');
                 $codes = $repository->findBy(array('user' => $user, 'problem' => $problem), array('date' => 'desc'), 1, 0);



                $em = $this->getDoctrine()->getManager();
		$postData = trim($postData);
                if (!$code) {
                    $error = 0;
                    if ($err == 1)
                        $error++;
                    $code = new Codes();
                    $code->setcontent("$postData");
                    $code->setlangage("$lang");
                    $code->setscore($score);
                    $code->seterror("$error");
                    $code->setdate(new \DateTime("$datenow"));
                    $code->setProblem($problem);
                    $code->setUser($this->getUser());
                    $em->persist($code);
                    $em->flush();
                    //score du challenge si pas de code pour le probleme:
                    if(!$codes){
                    if ($scor) {
                        $score_ancien = $accessor->getValue($scor, 'score');
                    } else {
                        $score_ancien = 0;
                    }
                    $score_nouveau = $score_ancien + $score / $nbr_prob;
                    }
                    else{
                        $score_ancien = $accessor->getValue($scor, 'score');
                        $code_s = $codes[0];
                        $score_an = $accessor->getValue($code_s, 'score');
                        $score_nouveau = $score_ancien - ($score_an / $nbr_prob) + ($score / $nbr_prob);
                    }
                } else {
                    $code_s = $codes[0];
                    $score_an = $accessor->getValue($code_s, 'score');
                    $error = $accessor->getValue($code, 'error');
                    if ($err == 1)
                        $error++;

                    $code->setcontent("$postData");
                    $code->setscore($score);
                    $code->setdate(new \DateTime("$datenow"));
                    $code->seterror("$error");
                    $em->flush();
                    //score du challenge:
                    if ($scor) {
                        $score_ancien = $accessor->getValue($scor, 'score');
                    } else {
                        $score_ancien = 0;
                    }
                    $score_nouveau = $score_ancien - ($score_an / $nbr_prob) + ($score / $nbr_prob);
                }


                $error_chall = 0;
                if ($scor) {
                    $date_final = $datenow;
                    $error_chall = $accessor->getValue($scor, 'error');
                    if ($err == 1) {
                        $error_chall++;
                    }
                } else {
                    if ($err == 1)
                        $error_chall = 1;
                    else
                        $error_chall = 0;
                }


                $lost = $datenow;
                for ($i = 0; $i < $error_chall; $i++) {
                    $lost = strtotime('+20 minutes', strtotime("$lost"));
                    $lost = date('Y-m-d H:i:s', $lost);
                }
                $date_final = $lost;




                if (!$scor) {
                    $scor = new Score();
                    $scor->setscore("$score_nouveau");
                    $scor->settime(new \DateTime("$datenow"));
                    $scor->setChallenge($challenge);
                    $scor->setError($error_chall);
                    $scor->settime_final(new \Datetime("$date_final"));
                    $scor->setUser($this->getUser());
                    $em->persist($scor);
                    $em->flush();
                } else {
                    $scor->setscore("$score_nouveau");
                    $scor->setError($error_chall);
                    $scor->settime_final(new \Datetime("$date_final"));
                    $scor->settime(new \DateTime("$datenow"));
                    $em->flush();
                }
            }



            if ($lang == "c") {
                return $this->render('CodeChallengeBundle:mode:c.html.twig', array('prob' => $prob,
                            'lang' => $lang,
                            'chall' => $chall,
                            'problem' => $problem,
                            'code' => $code,
                            'result' => $result,
                            'nbr_prob' => $nbr_prob,
                            'score' => $score,
                            'nbr_tests' => $nbr_tests,
                            'user' => $user,
                            'date' => $date_debut,
                            'duree' => $duree
                ));
            } else if ($lang == "cpp") {
                return $this->render('CodeChallengeBundle:mode:cpp.html.twig', array('prob' => $prob,
                            'lang' => $lang,
                            'chall' => $chall,
                            'problem' => $problem,
                            'code' => $code,
                            'result' => $result,
                            'nbr_prob' => $nbr_prob,
                            'score' => $score,
                            'nbr_tests' => $nbr_tests,
                            'user' => $user,
                            'date' => $date_debut,
                            'duree' => $duree
                ));
            } else if ($lang == "java") {
                return $this->render('CodeChallengeBundle:mode:java.html.twig', array('prob' => $prob,
                            'lang' => $lang,
                            'chall' => $chall,
                            'problem' => $problem,
                            'code' => $code,
                            'result' => $result,
                            'nbr_prob' => $nbr_prob,
                            'score' => $score,
                            'nbr_tests' => $nbr_tests,
                            'user' => $user,
                            'date' => $date_debut,
                            'duree' => $duree
                ));
            }/* else if ($lang == "python") {
              return $this->render('CodeChallengeBundle:mode:python.html.twig', array('prob' => $prob,
              'lang' => $lang,
              'chall' => $chall,
              'problem' => $problem,
              'code' => $code,
              'result' => $out,
              'nbr_prob' => $nbr_prob,
              'score' => $score,
              'nbr_tests' => $nbr_tests,
              'user' => $user,
              'date' => $date_debut,
              'duree' => $duree
              ));
              } *//* else if ($lang == "shell") {
              return $this->render('CodeChallengeBundle:mode:shell.html.twig', array('prob' => $prob,
              'lang' => $lang,
              'chall' => $chall,
              'problem' => $problem,
              'code' => $code,
              'result' => $out,
              'nbr_prob' => $nbr_prob,
              'score' => $score,
              'nbr_tests' => $nbr_tests,
              'user' => $user,
              'date' => $date_debut,
              'duree' => $duree
              ));
              } */ else {
                return $this->render('CodeChallengeBundle:mode:shell.html.twig', array('prob' => $prob,
                            'lang' => $lang,
                            'chall' => $chall,
                            'problem' => $problem,
                            'code' => $code,
                            'result' => $result,
                            'nbr_prob' => $nbr_prob,
                            'score' => $score,
                            'nbr_tests' => $nbr_tests,
                            'user' => $user,
                            'date' => $date_debut,
                            'duree' => $duree
                ));
            }
        } else {
            return $this->redirect($this->generateUrl('allchallenges'));
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

    public function compile($code, $input, $lang) {

        $user = 'ossec01'; //--> API username
        $pass = 'ossec2014'; //--> API password
        //language
        if ($lang == 'cpp')
            $lang = 1;
        else if ($lang == 'java')
            $lang = 10;
        else
            $lang = 11;

        $code = $code;
        //input
        $input = $input;

        $run = true;
        $private = true;

        //create new SoapClient
        $client = new SoapClient("http://ideone.com/api/1/service.wsdl");
        //create new submission
        $result = $client->createSubmission($user, $pass, $code, $lang, $input, $run, $private);
        //if submission is OK, get the status
        if ($result['error'] == 'OK') {
            $status = $client->getSubmissionStatus($user, $pass, $result['link']);
            if ($status['error'] == 'OK') {
                //check if the status is 0, otherwise getSubmissionStatus again
                while ($status['status'] != 0) {
                    sleep(3); //sleep 3 seconds
                    $status = $client->getSubmissionStatus($user, $pass, $result['link']);
                }
                //finally get the submission results
                $details = $client->getSubmissionDetails($user, $pass, $result['link'], true, true, true, true, true);
                if ($details['error'] == 'OK') {
                    
                } else {
                    //we got some error
                }
            } else {
                //we got some error
            }
        } else {
            //we got some error
        }
        //output 
        return $details['output'];
    }

}
