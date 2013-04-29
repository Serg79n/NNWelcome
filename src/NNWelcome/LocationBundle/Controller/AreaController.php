<?php

namespace NNWelcome\LocationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use NNWelcome\LocationBundle\Entity\Area;
use NNWelcome\LocationBundle\Form\AreaType;
use NNWelcome\LocationBundle\Form\AreaFilterType;

/**
 * Area controller.
 *
 */
class AreaController extends Controller {

    private $query;
    private $metadata;
    private static $current_class;
    
    /**
     * @Template()
     */
    public function indexAction(Request $request, $city_id) {
        $city = $this->getDoctrine()->getManager()->getRepository('LocationBundle:City')->find($city_id);
        if(!$city){
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find city with id = "%id%".', array('%id%' => $city_id))
            );
            return $this->redirect($this->generateUrl('admin_city'));
        }
        
        $page = $request->query->get('page');

        if ($page) {
            $request->getSession()->set('admin_area_page', $page);
        } elseif ($request->getSession()->has('admin_area_page')) {
            $page = $request->getSession()->get('admin_area_page');
        } else {
            $page = 1;
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $this->BuildQuery($request, $city_id), $page, /* page number */ 20/* limit per page */
        );
        $deleteForm = $this->createEmptyDeleteForm();

        $filterForm = $this->createForm(new AreaFilterType(), $this->getFilterModel($request));

        return array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView(),
            'filter_form' => $filterForm->createView(),
            'city_id' => $city_id
        );
    }

    public function filterAction(Request $request, $city_id) {
        $form = $this->createForm(new AreaFilterType());
        $form->bind($request);

        $this->setFilter($request, $form);
        $request->getSession()->set('admin_area_page', 1);

        return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));
    }

    public function resetFilterAction(Request $request, $city_id) {
        $request->getSession()->remove('admin_area_filter');
        $request->getSession()->set('admin_area_page', 1);

        return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));
    }

    private function BuildQuery($request, $city_id) {
        $em = $this->getDoctrine()->getManager();
        $this->query = $em->getRepository('LocationBundle:Area')
                ->retriveArea($request, $city_id);

        $this->getFilter($request);

        return $this->query->getQuery()->setHint(
                        \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')->execute();
    }

    private function setFilter($request, $form) {
        $filter = array();
        foreach ($form as $one) {
            if ($one->getData() !== null) {
                $type = $this->getDbType('NNWelcome\LocationBundle\Entity\Area', $one->getName());
                if ($type == 'entity')
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData()->getId());
                else
                    $filter[$one->getName()] = array('type' => $type, 'value' => $one->getData());
            }
        }
        $request->getSession()->set('admin_area_filter', $filter);
    }

    private function getFilter($request) {
        if ($request->getSession()->has('admin_area_filter')) {
            foreach ($request->getSession()->get('admin_area_filter') as $i => $one) {
                call_user_func_array(array($this, 'add' . ucfirst($one['type']) . 'Filter'), array($i, $one['value']));
            }
        }
    }

    private function getFilterModel($request) {
        $entity = new Area();
        $entity->setIsActive(null);
        if ($request->getSession()->has('admin_area_filter')) {
            foreach ($request->getSession()->get('admin_area_filter') as $i => $one) {
                if ($one['type'] == 'entity') {
                    $metadata = $this->getMetadatas('NNWelcome\LocationBundle\Entity\Area');
                    $targetClass = $metadata->getAssociationTargetClass($i);
                    $one['value'] = $this->getDoctrine()->getManager()->getRepository($targetClass)->find($one['value']);
                }
                $entity->set($i, $one['value']);
            }
        }
        //exit;
        return $entity;
    }

    private function addBooleanFilter($field, $value) {
        if ("" !== $value) {
            $this->query->andWhere(sprintf('c.%s = :%s', $field, $field));
            $this->query->setParameter($field, $value);
        }
    }

    private function addStringFilter($field, $value) {
        $this->query->andWhere(sprintf('c.%s LIKE :%s', $field, $field));
        $this->query->setParameter($field, '%' . $value . '%');
    }

    private function addTextFilter($field, $value) {
        $this->query->andWhere(sprintf('c.%s LIKE :%s', $field, $field));
        $this->query->setParameter($field, '%' . $value . '%');
    }

    public function addEntityFilter($field, $value) {
        $metadata = $this->getMetadatas('NNWelcome\LocationBundle\Entity\Area');
        $targetClass = $metadata->getAssociationTargetClass($field);

        $this->query->innerJoin($targetClass, $field);
        $this->query->andWhere($field . '.id = :' . $field);
        $this->query->andWhere(sprintf('c.%s = :%s', $field, $field));
        $this->query->setParameter($field, $value);
    }

    protected function getMetadatas($class = null) {
        if ($class) {
            self::$current_class = $class;
        }

        if (isset($this->metadata[self::$current_class]) || !$class) {
            return $this->metadata[self::$current_class];
        }

        if (!$this->getDoctrine()->getEntityManager()->getConfiguration()->getMetadataDriverImpl()->isTransient($class)) {
            $this->metadata[self::$current_class] = $this->getDoctrine()->getEntityManager()->getClassMetadata($class);
        }

        return $this->metadata[self::$current_class];
    }

    public function getDbType($class, $fieldName) {
        $metadata = $this->getMetadatas($class);

        if ($metadata->hasAssociation($fieldName)) {
            if ($metadata->isSingleValuedAssociation($fieldName)) {
                return 'entity';
            } else {
                return 'collection';
            }
        }

        if ($metadata->hasField($fieldName)) {
            return $metadata->getTypeOfField($fieldName);
        }

        return 'virtual';
    }

    /**
     * @Template()
     */
    public function newAction(Request $request, $city_id) {
        $city = $this->getDoctrine()->getManager()->getRepository('LocationBundle:City')->find($city_id);
        if(!$city){
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find city with id = "%id%".', array('%id%' => $city_id))
            );
            return $this->redirect($this->generateUrl('admin_city'));
        }
        
        $entity = new Area();
        $entity->setCity($city);

        $form = $this->createForm(new AreaType(), $entity);

        $page = 1;
        if ($request->getSession()->has('admin_area_page')) {
            $page = $request->getSession()->get('admin_area_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form' => $form->createView(),
            'city_id' => $city_id
        );
    }

    /**
     * @Template("LocationBundle:Area:new.html.twig")
     */
    public function createAction(Request $request, $city_id) {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('LocationBundle:City')->find($city_id);
        if(!$city){
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find city with id = "%id%".', array('%id%' => $city_id))
            );
            return $this->redirect($this->generateUrl('admin_city'));
        }
        
        $entity = new Area();
        $entity->setCity($city);
        $form = $this->createForm(new AreaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('Entry is successfully saved!')
            );

            if ($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));

            return $this->redirect($this->generateUrl('admin_area_edit', array('id' => $entity->getId(), 'city_id' => $city_id)));
        }

        $this->get('session')->setFlash('error', $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        $page = 1;
        if ($request->getSession()->has('admin_area_page')) {
            $page = $request->getSession()->get('admin_area_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'form' => $form->createView(),
            'city_id' => $city_id
        );
    }

    /**
     * @Template()
     */
    public function editAction($id, Request $request, $city_id) {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('LocationBundle:City')->find($city_id);
        if(!$city){
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find city with id = "%id%".', array('%id%' => $city_id))
            );
            return $this->redirect($this->generateUrl('admin_city'));
        }
        
        $entity = $em->getRepository('LocationBundle:Area')->find($id);

        if (!$entity) {
            $this->get('session')->setFlash('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));
        }

        $editForm = $this->createForm(new AreaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $page = 1;
        if ($request->getSession()->has('admin_area_page')) {
            $page = $request->getSession()->get('admin_area_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'city_id' => $city_id
        );
    }

    /**
     * @Template("LocationBundle:Area:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $city_id) {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('LocationBundle:City')->find($city_id);
        if(!$city){
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find city with id = "%id%".', array('%id%' => $city_id))
            );
            return $this->redirect($this->generateUrl('admin_city'));
        }
        
        $entity = $em->getRepository('LocationBundle:Area')->find($id);

        if (!$entity) {
            $this->get('session')->setFlash('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AreaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('"%title%" is successfully updated!', array('%title%' => $entity->getTitle()))
            );

            if ($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));

            return $this->redirect($this->generateUrl('admin_area_edit', array('id' => $entity->getId(), 'city_id' => $city_id)));
        }

        $this->get('session')->setFlash('error', $this->get('translator')->trans('Correct validation errors and try saving again.')
        );

        $page = 1;
        if ($request->getSession()->has('admin_area_page')) {
            $page = $request->getSession()->get('admin_area_page');
        }
        return array(
            'page' => $page,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'city_id' => $city_id
        );
    }

    public function deleteAction(Request $request, $id, $city_id) {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('LocationBundle:City')->find($city_id);
        if(!$city){
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find city with id = "%id%".', array('%id%' => $city_id))
            );
            return $this->redirect($this->generateUrl('admin_city'));
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LocationBundle:Area')->find($id);

            if (!$entity) {
                $this->get('session')->setFlash('warnin', $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->setFlash('notice', $this->get('translator')->trans('"%title%" is successfully deleted!', array('%title%' => $entity->getTitle()))
            );
        } else {
            $this->get('session')->setFlash('error', $this->get('translator')->trans('Error deleting "%title%".', array('%title%' => $entity->getTitle()))
            );
        }

        return $this->redirect($this->generateUrl('admin_area', array('city_id' => $city_id)));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    private function createEmptyDeleteForm() {
        return $this->createFormBuilder()
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    public function changeActiveAction(Request $request) {
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $active = $request->request->get('active');

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LocationBundle:Area')->find($id);

            if (!$entity) {
                return null;
            }

            if ($active)
                $active = 0;
            else
                $active = 1;

            $entity->setIsActive($active);
            $em->persist($entity);
            $em->flush();
        }
        else {
            $active = "";
        }
        return $this->render(
                        'LocationBundle:Area:_ajaxResponse.html.twig', array(
                    'text' => $active
                        )
        );
    }

}
