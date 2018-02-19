<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Todo;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      $todo = $this
      ->getDoctrine()
      ->getRepository('AppBundle:Todo')
      ->findAll();


        // replace this example code with whatever you need
        return $this->render('default/home.html.twig', array('todo' => $todo));
    }

    /**
    * @Route("/create", name="crear")
    */
    public function createAction(Request $request){
      $todo = new Todo();

      $form = $this->CreateFormBuilder($todo)
      ->add('titulo', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin:25px')))
      ->add('enlace', UrlType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin:25px')))
      ->add('prioridad', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
        'choices'  => array(
        'Alto' => 'Alto',
        'Normal' => 'Normal',
        'Bajo' => 'Bajo'),))
      ->add('estado', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
        'choices'  => array(
        'Por hacer' => 'Por hacer',
        'Haciendo' => 'Haciendo',
        'Hecho' => 'Hecho'),))
      ->add('fechaEntrega', DateTimeType::class, array('attr'=>array('class'=>'formcontrol', 'style'=>'margin-bottom:15px')))
      ->add('Guardar', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary', 'style' => 'margin:15px')))
      ->getForm();

      $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $title = $form['titulo']->getData();
          $enlace = $form['enlace']->getData();
          $estado = $form['estado']->getData();
          $prioridad = $form['prioridad']->getData();
          $fechaEntrega = $form['fechaEntrega']->getData();

          $todo->setTitulo($title);
          $todo->setEnlace($enlace);
          $todo->setEstado($estado);
          $todo->setPrioridad($prioridad);
          $todo->setFechaEntrega($fechaEntrega);

          $em = $this->getDoctrine()->getManager();
          $em->persist($todo);
          $em->flush();
          $this->addFlash(
            'notice', 'Actividad creada'
          );
          return $this->redirectToRoute('homepage');
      }

      return $this->render('crud/crear.html.twig', array('form' => $form->createView(), 'todo' => $todo));
    }

    /**
    * @Route("/edit/{id}", name="editar")
    */

    public function editAction($id, Request $request){
      $todo = $this->getDoctrine()
       ->getRepository('AppBundle:Todo')
       ->find($id);

       $form = $this->CreateFormBuilder($todo)
       ->add('titulo', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin:25px')))
       ->add('enlace', UrlType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin:25px')))
       ->add('prioridad', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
         'choices'  => array(
         'Alto' => 'Alto',
         'Normal' => 'Normal',
         'Bajo' => 'Bajo'),))
       ->add('estado', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
         'choices'  => array(
         'Por hacer' => 'Por hacer',
         'Haciendo' => 'Haciendo',
         'Hecho' => 'Hecho'),))
       ->add('fechaEntrega', DateTimeType::class, array('attr'=>array('class'=>'formcontrol', 'style'=>'margin-bottom:15px')))
       ->add('Guardar', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary', 'style' => 'margin:15px')))
       ->getForm();

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
         $title = $form['titulo']->getData();
         $enlace = $form['enlace']->getData();
         $estado = $form['estado']->getData();
         $prioridad = $form['prioridad']->getData();
         $fechaEntrega = $form['fechaEntrega']->getData();

         $todo->setTitulo($title);
         $todo->setEnlace($enlace);
         $todo->setEstado($estado);
         $todo->setPrioridad($prioridad);
         $todo->setFechaEntrega($fechaEntrega);

         $em = $this->getDoctrine()->getManager();
         $em->persist($todo);
         $em->flush();
         $this->addFlash(
           'notice', 'Actividad editada'
         );
         return $this->redirectToRoute('homepage');
       }

     return $this->render('crud/editar.html.twig', array(
       'todo' => $todo,
       'form' => $form->createView(),
     ));
    }
}
