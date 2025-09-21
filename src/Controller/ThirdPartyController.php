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
    public function fetch(Request $request)
    {
        $userId = $request->query->get('userId');
        $url = 'https://jsonplaceholder.typicode.com/posts';

        if ($userId) {
            $url .= '?userId=' . intval($userId);
        }
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

    #[Route('/post', name: '_post', methods: ['POST'])]
    public function post(Request $request)
    {
        $url = 'https://jsonplaceholder.typicode.com/posts';
        $data = $request->request->all();
        $body = $this->createForm(ThirdPartyType::class, $data);

        $body->submit($data);

        if ($body->isValid() && $body->isSubmitted()) {
            $request = $this->thirdPartyService->postData($url, $data);

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

    #[Route('/put/{id}', name: '_put', methods: ['POST'])]
    public function put(Request $request, $id)
    {
        $url = 'https://jsonplaceholder.typicode.com/posts/' . $id;
        $data = $request->request->all();
        $body = $this->createForm(ThirdPartyType::class, $data);

        $body->submit($data);
        if ($body->isValid() && $body->isSubmitted()) {
            $request = $this->thirdPartyService->putData($url, $data);

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
                ->addMessage('Data updated successfully')
                ->setData($request['data']);

            return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_OK));
        }

        $this->rest->failed()->setFormErrors($body->getErrors(true));
        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_BAD_REQUEST));
    }

    #[Route('/delete/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete($id)
    {
        $url = 'https://jsonplaceholder.typicode.com/posts/' . $id;
        $request = $this->thirdPartyService->deleteData($url);

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
            ->addMessage('Data deleted successfully')
            ->setData($request['data']);

        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_OK));
    }

    #[Route('/comments/{id}', name: '_comments', methods: ['GET'])]
    public function comments($id)
    {
        $url = 'https://jsonplaceholder.typicode.com/posts/' . $id . '/comments';
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
            ->addMessage('Comments fetched successfully')
            ->setData($request['data']);

        return $this->handleView($this->view($this->rest->getResponse(), Response::HTTP_OK));
    }


}
