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

namespace VIB\FliesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * FlyCrossType class
 *
 * @author Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 */
class CrossVialSimpleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "crossvialsimple";
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('setupDate', 'datepicker', array('label' => 'Setup date'))
                ->add('flipDate', 'datepicker', array('label' => 'Check date'))
                ->add('virgin', 'text_entity', array(
                        'property'     => 'id',
                        'class' => 'VIBFliesBundle:Vial',
                        'format'    => '%06d',
                        'label' => 'Virgin vial',
                        'attr' => array('class' => 'barcode')))
                ->add('virginName', 'text', array(
                        'label' => 'Virgin genotype',
                        'required' => false))
                ->add('male', 'text_entity', array(
                        'property'     => 'id',
                        'class' => 'VIBFliesBundle:Vial',
                        'format'    => '%06d',
                        'label' => 'Male vial',
                        'attr' => array('class' => 'barcode')))
                ->add('maleName', 'text', array(
                        'label' => 'Male genotype',
                        'required' => false))
                ->add('notes', 'textarea', array(
                        'label' => 'Notes',
                        'required' => false))
                ->add('incubator', 'entity', array(
                        'property'     => 'name',
                        'class' => 'VIBFliesBundle:Incubator',
                        'label' => 'Incubator'));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VIB\FliesBundle\Entity\CrossVial',
            'error_mapping' => array(
                'maleValid' => 'maleName',
                'virginValid' => 'virginName',
            )
        ));
    }
}

?>