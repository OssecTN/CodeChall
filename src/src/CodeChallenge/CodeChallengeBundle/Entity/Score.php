<?php

namespace CodeChallenge\CodeChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table("score")
 * @ORM\Entity(repositoryClass="CodeChallenge\CodeChallengeBundle\Entity\ScoreRepository")
 */
class Score
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
     * @ORM\ManyToOne(targetEntity="User\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="error", type="integer")
     */
    private $error;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_final", type="datetime")
     */
    private $time_final;


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
     * Set score
     *
     * @param integer $score
     * @return Score
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
     * Set error
     *
     * @param integer $score
     * @return Score
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }
    
    /**
     * Get error
     *
     * @return integer 
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Score
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    
    /**
     * Set time_final
     *
     * @param \DateTime $time_final
     * @return Score
     */
    public function setTime_final($time_final)
    {
        $this->time_final = $time_final;

        return $this;
    }
    
    /**
     * Get time_final
     *
     * @return \DateTime 
     */
    public function getTime_final()
    {
        return $this->time_final;
    }
    
    /**
     * Set challenge
     *
     * @param \CodeChallenge\CodeChallengeBundle\Entity\Challenges $challenge
     * @return Score
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
     * @return Score
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
