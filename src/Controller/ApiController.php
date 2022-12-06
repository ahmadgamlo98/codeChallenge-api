<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\Query\AST\Functions\LengthFunction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use PhpParser\Node\Stmt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }
    /**
     *  @Route("/api/post_api", name="post_api", methods={"POST"})
     */

    public function post_api(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $customer = new Customer();
        $parameter = json_decode($request->getContent(), true);
       
        if ($parameter != null) {
            $customer->setName(isset($parameter['name']) ? $parameter['name'] : 0);
            $customer->setAddress(isset($parameter['address']) ? $parameter['address'] : 0);
            $customer->setMobilenumber(isset($parameter['mobilenumber']) ? $parameter['mobilenumber'] : 0);
            $customer->setCountryname(isset($parameter['countryname']) ? $parameter['countryname'] : 0);
        } else {
            $customer->setName($request->request->get('name'));
            $customer->setAddress($request->request->get('address'));
            $customer->setMobilenumber($request->request->get('mobilenumber'));
            $customer->setCountryname($request->request->get('countryname'));
        }
        
        $entityManager->persist($customer);
        $entityManager->flush();
        return $this->json('Inserted Successfuly');
    }

    /**
     *  @Route("/api/update_api/{id}", name="update_api", methods={"PUT"})
     */

    public function update_api(Request $request, PersistenceManagerRegistry $doctrine, $id): Response
    {
        $data = $doctrine->getRepository(Customer::class)->find($id);
        $parameter = json_decode($request->getContent(), true);

        if ($parameter != null) {
            $data->setName(isset($parameter['name']) ? $parameter['name'] : 0);
            $data->setAddress(isset($parameter['address']) ? $parameter['address'] : 0);
            $data->setMobilenumber(isset($parameter['mobilenumber']) ? $parameter['mobilenumber'] : 0);
            $data->setCountryname(isset($parameter['countryname']) ? $parameter['countryname'] : 0);
        } else {
            $data->setName($request->request->get('name'));
            $data->setAddress($request->request->get('address'));
            $data->setMobilenumber($request->request->get('mobilenumber'));
            $data->setCountryname($request->request->get('countryname'));
        }

        $em = $doctrine->getManager();
        $em->persist($data);
        $em->flush();
        return $this->json('Updated Successfuly');
    }

    /**
     *  @Route("/api/delete_api/{id}", name="delete_api", methods={"DELETE"})
     */

    public function delete_api(PersistenceManagerRegistry $doctrine, $id): Response
    {
        $data = $doctrine->getRepository(Customer::class)->find($id);

        $em = $doctrine->getManager();
        $em->remove($data);
        $em->flush();
        return $this->json('Deleted Successfuly');
    }

    /**
     *  @Route("/api/fetchall_api", name="fetchall_api", methods={"GET"})
     */

    public function fetchall_api(PersistenceManagerRegistry $doctrine): Response
    {
        $data = $doctrine->getRepository(Customer::class)->findAll();
        foreach ($data as $d) {
            $res[] = [
                'id' => $d->getId(),
                'name' => $d->getName(),
                'address' => $d->getAddress(),
                'mobilenumber' => $d->getMobilenumber(),
                'countryname' => $d->getCountryname(),

            ];
        }
        return $this->json(
            $res
        );
    }


    /**
     *  @Route("/api/fetchbyid_api/{id}", name="fetchbyid_api", methods={"GET"})
     */

    public function fetchbyid_api(PersistenceManagerRegistry $doctrine, $id): Response
    {
        $data = $doctrine->getRepository(Customer::class)->find($id);

        $res[] = [
            'id' =>  $data->getId(),
            'name' =>  $data->getName(),
            'address' =>  $data->getAddress(),
            'mobilenumber' =>  $data->getMobilenumber(),
            'countryname' =>  $data->getCountryname()
        ];

        return $this->json(
            $res
        );
    }
}
