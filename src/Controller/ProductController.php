<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/list", name="user_list", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function userProductList (ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        return $this->render('product/user/userList.html.twig', [
            'products' => $productRepository->findByUser($user, ['category'=>'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());
            $product->setIsHated(false);
            $product->setIsFavorite(false);
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produit ajouté à votre liste');

            return $this->redirectToRoute('product_user_list');
        }

        return $this->render('product/user/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/{id}/addfavorite", name="add_favorite", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param Product $product
     * @return Response
     */
    public function addFavorite(
        Request $request,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        Product $product
    ): Response {
        if ( !$product->getIsFavorite()) {
            $product->setIsFavorite(true);
            $product->setIsHated(false);
        } else {
            $product->setIsFavorite(false);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_user_list');
    }

    /**
     * @Route("/{id}/addhated", name="add_hated", methods={"GET","POST"})
     * @param Request $request
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function addHated(
        Request $request,
        Product $product,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ): Response {
        if (!$product->getIsHated()){
            $product->setIsHated(true);
            $product->setIsFavorite(false);
        } else {
            $product->setIsHated(false);
        }
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json([
            'isHated' => $product->getIsHated()
        ]);
    }
}
