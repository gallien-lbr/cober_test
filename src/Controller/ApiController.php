<?php

namespace App\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(EntityManagerInterface $em)
    {
        $data = $em->getRepository(Company::class)->findAll();

        dd($data);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    /**
     * @Route("/api/findBySiren/{siren}", name="api_find_by_siren")
     */
    public function getCompanyBySiren(EntityManagerInterface $em, SerializerInterface $serializer, int $siren)
    {
        $data = $em->getRepository(Company::class)->findBySiren($siren);
        if(empty($data)){
            $data = ['message' => 'no company were found'];
        }
        $serializedEntity = $serializer->serialize($data, 'json');
        return new Response($serializedEntity, 200, array('Content-Type' => 'application/json'));
    }


}
