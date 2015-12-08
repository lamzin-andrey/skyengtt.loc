<?php

namespace SkyengTT\SkyengTTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ErrorStat
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ErrorStat
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
     * @var integer
     *
     * @ORM\Column(name="quest_id", type="integer")
     */
    private $quest_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="answer_id", type="integer")
     */
    private $answer_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;


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
     * Set quest_id
     *
     * @param integer $questId
     * @return ErrorStat
     */
    public function setQuestId($questId)
    {
        $this->quest_id = $questId;

        return $this;
    }

    /**
     * Get quest_id
     *
     * @return integer 
     */
    public function getQuestId()
    {
        return $this->quest_id;
    }

    /**
     * Set answer_id
     *
     * @param integer $answerId
     * @return ErrorStat
     */
    public function setAnswerId($answerId)
    {
        $this->answer_id = $answerId;

        return $this;
    }

    /**
     * Get answer_id
     *
     * @return integer 
     */
    public function getAnswerId()
    {
        return $this->answer_id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ErrorStat
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
