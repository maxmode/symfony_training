<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BatterySubmit;
use AppBundle\Form\BatterySubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class BatteriesController extends Controller
{
    /**
     * @Route("/batteries/", name="batteries")
     */
    public function indexAction(EntityManagerInterface $em)
    {
        $data = $em->getRepository('AppBundle:BatterySubmit')
                   ->findAllCountByType();;

        return $this->render('batteries/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'data' => $data
        ]);
    }

    /**
     * @Route("/batteries/form/", name="batteries/form")
     */
    public function formAction()
    {
        $batterySubmit = new BatterySubmit();
        $form = $this->createForm(BatterySubmitType::class, $batterySubmit, array(
            'action' => $this->generateUrl('batteries/submit')
        ));

        return $this->render('batteries/form.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/batteries/submit/", name="batteries/submit")
     */
    public function submitAction(Request $request)
    {
        $batterySubmit = new BatterySubmit();
        $form = $this->createForm(BatterySubmitType::class, $batterySubmit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $batterySubmit = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($batterySubmit);
            $em->flush();
            return $this->redirectToRoute('batteries');
        } else {
            return $this->redirectToRoute('batteries/form');
        }
    }
}
