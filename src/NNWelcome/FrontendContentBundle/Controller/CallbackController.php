<?php

namespace NNWelcome\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use NNWelcome\CallbackBundle\Entity\Callback;
use NNWelcome\CallbackBundle\Form\FrontendCallbackType;

class CallbackController extends Controller
{
     public function newCallbackAction(){
         $entity = new Callback();
         $form   = $this->createForm(new FrontendCallbackType(), $entity);
         
         return $this->render(
              'FrontendContentBundle:Callback:_newCallback.html.twig', array(
              'form' => $form->createView()
         ));
     }
     
     public function requestCallbackAction(Request $request){
         $entity = new Callback();
         $form = $this->createForm(new FrontendCallbackType(), $entity);
         $form->bind($request);

         if ($form->isValid()) {
             $em = $this->getDoctrine()->getManager();
             $entity->setIsReceived(false);
             $em->persist($entity);
             $em->flush();
             
             return $this->render(
                  'FrontendContentBundle:Callback:_callbackSaved.html.twig', array()
             );
         }
         
         return $this->render(
             'FrontendContentBundle:Callback:_newCallback.html.twig', array(
             'form' => $form->createView()
         ));
     }
}
