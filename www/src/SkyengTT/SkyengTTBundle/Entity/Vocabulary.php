<?php

namespace SkyengTT\SkyengTTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vocabulary
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Vocabulary
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
     * @ORM\Column(name="eng_word", type="string", length=32)
     */
    private $eng_word;

    /**
     * @var string
     *
     * @ORM\Column(name="rus_word", type="string", length=32)
     */
    private $rus_word;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="answer_id", type="integer", options={"default":0})
     */
    private $answer_id;


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
     * Set eng_word
     *
     * @param string $engWord
     * @return Vocabulary
     */
    public function setEngWord($engWord)
    {
        $this->eng_word = $engWord;

        return $this;
    }

    /**
     * Get eng_word
     *
     * @return string 
     */
    public function getEngWord()
    {
        return $this->eng_word;
    }

    /**
     * Set rus_word
     *
     * @param string $rusWord
     * @return Vocabulary
     */
    public function setRusWord($rusWord)
    {
        $this->rus_word = $rusWord;

        return $this;
    }

    /**
     * Get rus_word
     *
     * @return string 
     */
    public function getRusWord()
    {
        return $this->rus_word;
    }
    
    /**
     * Set answer_id
     *
     * @param integer $AnswerId
     * @return Vocabulary
     */
    public function setAnswerId($answerId)
    {
        $this->answer_id = $answerId;

        return $this;
    }

    /**
     * Get answer_id
     *
     * @return string 
     */
    public function getAnswerId()
    {
        return $this->answer_id;
    }
}
