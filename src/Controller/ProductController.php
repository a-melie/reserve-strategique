<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Product;
use App\Entity\Program;
use App\Form\ProductType;
use App\Form\ProgramSearchType;
use App\Form\SearchFormType;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 * @IsGranted("ROLE_USER")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/keyword", name="ajax_keyword", methods={"GET","POST", "DELETE"})
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function getListOfProductNames(ProductRepository $productRepository, Request $request)
    {
        $keyword = $request->query->get('keyword');
        $products = $productRepository->searchByKeyWords([$keyword]);
       $names = [];
       foreach ($products as $product) {
           $names[]= $product->getName();
       }
       return $this->json($names, 200);
    }
    /**
     * @Route("/list", name="user_list", methods={"GET","POST", "DELETE"})
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param SearchData $searchData
     * @return Response
     */
    public function userProductList (
        Request $request,
        ProductRepository $productRepository,
        SearchData $searchData
    ): Response {
        $products = $productRepository->findByUser($this->getUser(), ['category'=>'ASC']);

        $form = $this->createForm(SearchFormType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findSearch($searchData);
        }

        return $this->render('product/user/userList.html.twig', [
            'products' => $products,
            'form'=>$form->createView(),
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
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_user_list');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé de votre liste');

        }

        return $this->redirectToRoute('product_user_list');
    }

    /**
     * @Route("/{id}/addfavorite", name="add_favorite", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Product $product
     * @return Response
     */
    public function addFavorite(
        Request $request,
        EntityManagerInterface $entityManager,
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

        return $this->json([
            'isFavorite' => $product->getIsFavorite()
        ]);
    }

    /**
     * @Route("/{id}/addhated", name="add_hated", methods={"GET","POST"})
     * @param Request $request
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function addHated(
        Request $request,
        Product $product,
        EntityManagerInterface $entityManager
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

    /**
     *
     * @Route("/favorites", name="favorites", methods={"GET","POST"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function indexFavorite(ProductRepository $productRepository): Response
    {
       return $this->render('product/user/favorites.html.twig',[
           'products' => $productRepository->findFavoritesOrHated($this->getUser(), 'isFavorite'),
       ]);
    }

    /**
     * @Route("/hated", name="hated", methods={"GET","POST"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function indexhated(ProductRepository $productRepository): Response
    {
        return $this->render('product/user/hated.html.twig',[
            'products' => $productRepository->findFavoritesOrHated($this->getUser(), 'isHated'),
        ]);

    }
}
