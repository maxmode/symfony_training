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
        $data = $em->getRepository(BatterySubmit::class)
                   ->findAllCountByType();

        return $this->render('batteries/index.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/batteries/form/", name="batteries/form")
     */
    public function formAction(Request $request)
    {
        $batterySubmit = new BatterySubmit();
        //todo: try to set null as $batterySubmit - form will generate entity for you. Also "action" by default is current page, which is fine in our case - no need to override it 
        $form = $this->createForm(BatterySubmitType::class, $batterySubmit, array(
            'action' => $this->generateUrl('batteries/form')
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $batterySubmit = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($batterySubmit);
            $em->flush();
            return $this->redirectToRoute('batteries');
        }

        return $this->render('batteries/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
