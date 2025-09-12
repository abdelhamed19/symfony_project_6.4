<?php

namespace App\Job\MessageHandler;

use App\Entity\Article;
use App\Entity\Category;
use App\Services\ArticleService;
use Doctrine\ORM\EntityManagerInterface;
use App\Job\Message\CategoryDeleteMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CategoryDeleteMessageHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private ArticleService $articleService
    ) {}
    
    public function __invoke(CategoryDeleteMessage $message)
    {
        $categoryId = $message->getCategoryId();
        $category = $this->em->getRepository(Category::class)->find($categoryId);
        if ($category) {
            $this->articleService->deleteArticlesByCategoryId($category);
            $this->em->remove($category);
            $this->em->flush();
        }
    }
    
}
