<?php

namespace App\Controller;

use App\Form\ThirdPartyType;
use App\Services\RestHelperService;
use App\Services\ThirdPartyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('api/third/party', name: 'app_third_party')]
final class ThirdPartyController extends AbstractFOSRestController
{
    public function __construct(
        private ThirdPartyService $thirdPartyService,
        private RestHelperService $rest
    ) {}

    #[Route('/fetch', name: '_fetch')]
    public function fetch()
    {
        $url = 'https://api.github.com/repos/symfony/symfony-docs';
        $request = $this->thirdPartyService->fetchData($url);
        if (!$request['success']) {
            $this
                ->rest
                ->failed()
                ->addMessage($request['error'])
                ->setData(null);

            return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
        }

        $this
            ->rest
            ->succeeded()
            ->addMessage('Data fetched successfully')
            ->setData($request['data']);

        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_OK));
    }

    #[Route('/post', name: '_post')]
    public function post(Request $request)
    {
        $url = 'https://jsonplaceholder.typicode.com/posts';
        $data = $request->request->all();
        $body = $this->createForm(ThirdPartyType::class, $data);

        $body->submit($data);

        if ($body->isValid() && $body->isSubmitted()) {
            $request = $this->thirdPartyService->postData($url, $body);
            
            if (!$request['success']) {
                $this
                    ->rest
                    ->failed()
                    ->addMessage($request['error'])
                    ->setData(null);

                return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
            }

            $this
                ->rest
                ->succeeded()
                ->addMessage('Data posted successfully')
                ->setData($request['data']);

            return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_OK));
        }

        $this->rest->failed()->setFormErrors($body->getErrors(true));
        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
    }
}
