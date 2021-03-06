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

use VIB\CoreBundle\Controller\CRUDController;

use VIB\FliesBundle\Form\IncubatorType;

/**
 * IncubatorController class
 *
 * @Route("/incubators")
 *
 * @author Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 */
class IncubatorController extends CRUDController
{
    /**
     * Construct StockController
     *
     */
    public function __construct()
    {
        $this->entityClass = 'VIB\FliesBundle\Entity\Incubator';
        $this->entityName  = 'incubator|incubators';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditForm()
    {
        return new IncubatorType();
    }

    /**
     * Generate links for putting stuff into incubator
     *
     * @Template()
     *
     * @return array
     */
    public function incubateAction()
    {
        return $this->menuAction();
    }

    /**
     * Generate links for incubator menu
     *
     * @Template()
     *
     * @return array
     */
    public function menuAction()
    {
        $entities = $this->getObjectManager()->findAll($this->getEntityClass());

        return array('entities' => $entities);
    }
}
