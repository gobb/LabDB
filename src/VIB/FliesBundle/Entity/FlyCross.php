<?php

/*
 * Copyright 2011 Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace VIB\FliesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * FlyStock class
 * 
 * @author Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 * 
 * @ORM\Entity(repositoryClass="VIB\FliesBundle\Repository\FlyCrossRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class FlyCross {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Serializer\Expose
     */
    protected $id; 
    
    /**
     * @ORM\ManyToOne(targetEntity="FlyVial", inversedBy="maleCrosses")
     * @Assert\NotNull(message = "Male vial must be specified")
     */
    protected $male;
        
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    protected $maleName;
    
    /**
     * @ORM\ManyToOne(targetEntity="FlyVial", inversedBy="virginCrosses")
     * @Assert\NotNull(message = "Virgin vial must be specified")
     */
    protected $virgin;
        
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    protected $virginName;
    
    /**
     * @ORM\OneToOne(targetEntity="FlyVial", mappedBy="cross", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $vial;
    
    /**
     * @ORM\OneToMany(targetEntity="FlyStock", mappedBy="sourceCross")
     */
    protected $stocks;

    /**
     * Construct FlyCross
     *
     * @param VIB\FliesBundle\Entity\FlyCross $template
     */ 
    public function __construct($template = null)
    {
        $this->vial = new \VIB\FliesBundle\Entity\FlyVial;
        $this->vial->setCross($this);
        
        if (null !== $template) {
            $this->setMale($template->getMale());
            $this->setMaleName($template->getMaleName());
            $this->setVirgin($template->getVirgin());
            $this->setVirginName($template->getVirginName());
            
            $this->vial->setSetupDate($template->getVial()->getSetupDate());
            $this->vial->setFlipDate($template->getVial()->getFlipDate());
        }
    }
    
    /**
     * Return string representation of FlyCross
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }
    
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
     * Get routable id
     *
     * @return integer
     */
    public function getRoutableId()
    {
        $vial = $this->getVial();
        
        if (null !== $vial) {
            return $vial->getId();
        } else {
            return null;
        }
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getVirginName() . " ☿ ✕ " . $this->getMaleName() . " ♂";
    }
    
    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getVirginName() . "\nX\n" . $this->getMaleName();
    }
    
    /**
     * Set male
     *
     * @param VIB\FliesBundle\Entity\FlyVial $male
     */
    public function setMale(\VIB\FliesBundle\Entity\FlyVial $male)
    {
        $this->male = $male;
        if ($this->male != null)
            if ($this->male->getStock() != null)
                $this->maleName = $this->male->getStock()->getName();
    }

    /**
     * Get male
     *
     * @return VIB\FliesBundle\Entity\FlyVial
     */
    public function getMale()
    {
        return $this->male;
    }
    
    /**
     * Set maleName
     *
     * @param string $maleName
     */
    public function setMaleName($maleName)
    {
        if ($this->male != null)
            if ($this->male->getStock() != null)
                $maleName = $this->male->getStock()->getName();
        $this->maleName = $maleName;
    }

    /**
     * Get maleName
     *
     * @return string
     */
    public function getMaleName()
    {
        return $this->maleName;
    }

    /**
     * Set virgin
     *
     * @param VIB\FliesBundle\Entity\FlyVial $virgin
     */
    public function setVirgin(\VIB\FliesBundle\Entity\FlyVial $virgin)
    {
        $this->virgin = $virgin;
        if ($this->virgin != null)
            if ($this->virgin->getStock() != null)
                $this->virginName = $this->virgin->getStock()->getName();
    }

    /**
     * Get virgin
     *
     * @return VIB\FliesBundle\Entity\FlyVial
     */
    public function getVirgin()
    {
        return $this->virgin;
    }
    
    /**
     * Set virginName
     *
     * @param string $virginName
     */
    public function setVirginName($virginName)
    {
        if ($this->virgin != null)
            if ($this->virgin->getStock() != null)
                $virginName = $this->virgin->getStock()->getName();
        $this->virginName = $virginName;
    }

    /**
     * Get maleName
     *
     * @return string
     */
    public function getVirginName()
    {
                return $this->virginName;
    }

    /**
     * Set vial
     *
     * @param VIB\FliesBundle\Entity\FlyVial $vial
     */
    public function setVial(\VIB\FliesBundle\Entity\FlyVial $vial)
    {
        $vial->setCross($this);
        $this->vial = $vial;
    }

    /**
     * Get vial
     *
     * @return VIB\FliesBundle\Entity\FlyVial
     */
    public function getVial()
    {
        return $this->vial;
    }

    /**
     * Get stocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getStocks()
    {
        return $this->stocks;
    }
    
    /**
     * Get crosses
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCrosses()
    {
        return $this->getVial()->getCrosses();
    }
    
    /**
     * Get living crosses
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLivingCrosses() {
        
        $livingCrosses = new ArrayCollection();
        
        foreach ($this->getVial()->getCrosses() as $cross) {
            if ($cross->getVial()->isAlive())
                $livingCrosses->add($cross);
        }
        
        return $livingCrosses;
    }
    
    /**
     * Check if male name is specified when male source is a cross
     * 
     * @Assert\True(message = "Male name must be specified")
     * 
     * @return boolean
     */
    public function isMaleValid() {
        $vial = $this->getMale();
        $cross = null !== $vial ? $vial->getCross() : null;
        
        return ! ((null !== $cross)&&(trim($this->getMaleName()) == ""));
    }
    
    /**
     * Check if virgin name is specified when virgin source is a cross
     * 
     * @Assert\True(message = "Virgin name must be specified")
     * 
     * @return boolean
     */
    public function isVirginValid() {
        $vial = $this->getVirgin();
        $cross = null !== $vial ? $vial->getCross() : null;
        
        return ! ((null !== $cross)&&(trim($this->getVirginName()) == ""));
    }
}
