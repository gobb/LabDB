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

namespace VIB\FliesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use VIB\FliesBundle\Form\StockVialType;
use VIB\FliesBundle\Form\StockVialSelectType;

/**
 * StockVialController class
 * 
 * @Route("/stocks/vials")
 *
 * @author Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 */
class StockVialController extends VialController {

    /**
     * Construct StockVialController
     * 
     */
    public function __construct()
    {
        $this->entityClass  = 'VIB\FliesBundle\Entity\StockVial';
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getEditForm() {
        return new StockVialType();
    }
    
    /**
     * List created stock vials
     * 
     * @param integer $vials
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCreated($vials)
    {
        $formResponse = $this->handleSelectForm(new SelectType());
        
        if (isset($formResponse['response'])) {
            return $formResponse['response'];
        } else if (isset($formResponse['form'])) {       
            return array(
                'vials' => $vials,
                'form' => $formResponse['form'],
                'pager' => null
            );
        }
    }
}