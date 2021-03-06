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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use VIB\CoreBundle\Controller\AbstractController;

use VIB\FliesBundle\Entity\Vial;
use VIB\FliesBundle\Entity\CrossVial;

use VIB\FliesBundle\Form\SearchType;

/**
 * Description of SearchController
 *
 * @Route("/search")
 *
 * @author Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 */
class SearchController extends AbstractController
{
    /**
     * Handle search request
     *
     * @Route("/")
     * @Method({"GET"})
     * @Template()
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        return $this->render('VIBFliesBundle:Search:search.html.twig');
    }

    /**
     * Render search form
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function formAction()
    {
        $form = $this->createForm(new SearchType());
        $msie_version = 10;
        $request = $this->get('request');
        $user_agent = $request->headers->get('User-Agent');
        $is_msie = strpos($user_agent, 'MSIE') !== false;
        if ($is_msie) {
            $matches = array();
            preg_match('/MSIE (.*?)\./', $user_agent, $matches);
            $msie_version = array_key_exists(1, $matches) ? $matches[1] : 0;
            if ($msie_version < 8) {
                $msie = true;
            } else {
                $msie = false;
            }
        } else {
            $msie = false;
        }

        return $this->render('VIBFliesBundle:Search:form.html.twig', array(
            'form' => $form->createView(),
            'msie' => $msie ));
    }

    /**
     * Handle search result
     *
     * @Route("/result/")
     * @Template()
     *
     * @return array
     */
    public function resultAction()
    {
        $om = $this->getObjectManager();
        $form = $this->createForm(new SearchType());
        $request = $this->get('request');
        $session = $request->getSession();

        if ($request->getMethod() == 'POST') {

            $form->bindRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $query = $data['query'];
                if ('' == $data['filter']) {
                    if (preg_match("/^R\d+$/",$query) === 1) {
                        $filter = 'rack';
                    } elseif (is_numeric($query)) {
                        $filter = 'vial';
                    } else {
                        $filter = 'stocks';
                    }
                } else {
                    $filter = $data['filter'];
                }
                $session->set('search_query',$query);
                $session->set('search_filter',$filter);
            }
        } else {
            $query = $session->get('search_query');
            $filter = $session->get('search_filter');
        }

        switch ($filter) {
            case 'crosses':
                $queryBuilder = $om->getRepository('VIB\FliesBundle\Entity\CrossVial')->search($query);
                $result = $this->getAclFilter()->apply($queryBuilder);
                break;
            case 'vial':
                $url = $this->generateUrl('vib_flies_vial_show', array('id' => $query));

                return $this->redirect($url);
                break;
            case 'rack':
                $url = $this->generateUrl('vib_flies_rack_show', array('id' => (integer) str_replace('R','',$query)));

                return $this->redirect($url);
                break;
            case 'stocks':
            default:
                $queryBuilder = $om->getRepository('VIB\FliesBundle\Entity\Stock')->search($query);
                $result = $this->getAclFilter()->apply($queryBuilder);
                break;
        }

        $paginator  = $this->getPaginator();
        $page = $this->getCurrentPage();
        $entities = $paginator->paginate($result, $page, 10);

        return array('entities' => $entities,
                     'query' => $query,
                     'filter' => $filter);
    }
}
