<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /** @var ObjectManager */
    protected $entityManager;

    public function __construct(Registry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @Route("/api/article", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        $allArticles = $this->entityManager->getRepository(Article::class)->findAll();
        $articleList = [];

        /** @var Article $article */
        foreach ($allArticles as $article) {
            $articleList[] = $article->toArray();
        }

        return new JsonResponse(
            $articleList,
            200
        );
    }
}
