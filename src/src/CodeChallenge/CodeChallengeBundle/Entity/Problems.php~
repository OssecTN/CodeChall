<?php

namespace CodeChallenge\CodeChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Problems
 *
 * @ORM\Table("problems")
 * @ORM\Entity(repositoryClass="CodeChallenge\CodeChallengeBundle\Repository\ProblemsRepository")
 */
class Problems
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
   
    /**
     * @ORM\ManyToOne(targetEntity="CodeChallenge\CodeChallengeBundle\Entity\Challenges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $challenge;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_tests", type="integer")
     */
    private $nbrTests;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Problems
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Problems
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set nbrTests
     *
     * @param integer $nbrTests
     * @return Problems
     */
    public function setNbrTests($nbrTests)
    {
        $this->nbrTests = $nbrTests;

        return $this;
    }

    /**
     * Get nbrTests
     *
     * @return integer 
     */
    public function getNbrTests()
    {
        return $this->nbrTests;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Problems
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return Problems
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set challenge
     *
     * @param \CodeChallenge\CodeChallengeBundle\Entity\Challenges $challenge
     * @return Problems
     */
    public function setChallenge(\CodeChallenge\CodeChallengeBundle\Entity\Challenges $challenge)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \CodeChallenge\CodeChallengeBundle\Entity\Challenges 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }
}
