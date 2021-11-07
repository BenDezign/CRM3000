<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'company_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/company.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/setAdmin/{company}/{user}/{source}', name: 'company_setAdmin', methods: ['GET'])]
    public function setAdmin(Company $company, User $user, $source = "companies"): Response
    {
        $company->setUserAdmin($user);
        $this->getDoctrine()->getManager()->flush();
        if ($source == "companies") {
            $html = $this->renderView('component/list_companies.html.twig', [
                'companies' => $user->getCompany(),
                'user' => $user
            ]);
        } else {
            $html = $this->renderView('component/list_user.html.twig', [
                'company' => $company,
                'users' => $company->getUsers()
            ]);
        }

        return $this->json(['code' => 200, 'html' => $html]);
    }

    #[Route('/{id}/edit', name: 'company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/company.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_index', [], Response::HTTP_SEE_OTHER);
    }
}
