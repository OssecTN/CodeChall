<?php

namespace CodeChallenge\CodeChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Challenges
 *
 * @ORM\Table("challenges")
 * @ORM\Entity(repositoryClass="CodeChallenge\CodeChallengeBundle\Repository\ChallengesRepository")
 */
class Challenges
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="integer")
     */
    private $duree;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_problems", type="integer")
     */
    private $nbrProblems;

    /**
     * @var string
     *
     * @ORM\Column(name="langage", type="string", length=255)
     */
    private $langage;
    
    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text")
     */
    private $info;


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
     * @return Challenges
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Challenges
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     * @return Challenges
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set nbrProblems
     *
     * @param integer $nbrProblems
     * @return Challenges
     */
    public function setNbrProblems($nbrProblems)
    {
        $this->nbrProblems = $nbrProblems;

        return $this;
    }

    /**
     * Get nbrProblems
     *
     * @return integer 
     */
    public function getNbrProblems()
    {
        return $this->nbrProblems;
    }

    /**
     * Set langage
     *
     * @param string $langage
     * @return Challenges
     */
    public function setLangage($langage)
    {
        $this->langage = $langage;

        return $this;
    }

    /**
     * Get langage
     *
     * @return string 
     */
    public function getLangage()
    {
        return $this->langage;
    }
    
    /**
     * Set info
     *
     * @param string $info
     * @return Challenges
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }
}
