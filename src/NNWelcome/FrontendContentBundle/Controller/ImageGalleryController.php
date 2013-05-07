<?php

namespace NNWelcome\FrontendContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use NNWelcome\ImageGalleryBundle\Entity\Image;
use NNWelcome\ImageGalleryBundle\Entity\ImageCategory;

class ImageGalleryController extends Controller
{
     
     public function GalleryOnHomePageAction($alias, $size){
         $em = $this->getDoctrine()->getRepository('ImageGalleryBundle:Image');
         $items = $em->GetGallery($alias);

         return $this->render(
             'FrontendContentBundle:ImageGallery:_galleryOnHomePage.html.twig', 
                 array(
                     'alias' => $alias,
                     'items' => $items, 
                     'size' => $size)
         );
     }
}
