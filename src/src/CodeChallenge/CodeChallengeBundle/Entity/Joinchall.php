<?php

namespace CodeChallenge\CodeChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Joinchall
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CodeChallenge\CodeChallengeBundle\Entity\JoinchallRepository")
 */
class Joinchall
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="CodeChallenge\CodeChallengeBundle\Entity\Challenges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $challenge;
    
    /**
     * @ORM\ManyToOne(targetEntity="User\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Joinchall
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set challenge
     *
     * @param \CodeChallenge\CodeChallengeBundle\Entity\Challenges $challenge
     * @return Joinchall
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

    /**
     * Set user
     *
     * @param \User\UserBundle\Entity\User $user
     * @return Joinchall
     */
    public function setUser(\User\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \User\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
