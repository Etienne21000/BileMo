<?php


namespace App\OpenApi;


use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decoration;

    public function __construct(OpenApiFactoryInterface $decoration)
    {
        $this->decoration = $decoration;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $context = []): OpenApi
    {
        $OpenApi = $this->decoration->__invoke($context);
        $this->setDocumentation($OpenApi);
        $this->checkBearerAuthJWT($OpenApi);
        return $OpenApi;
    }

    private function checkBearerAuthJWT($OpenApi) {
        $scheme = $OpenApi->getComponents()->getSecuritySchemes();
        $scheme['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);
        $OpenApi->withSecurity(['bearerAuth'=>[]]);
    }

    private function setDocumentation($OpenApi) {
        foreach ($OpenApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
                $OpenApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }
        foreach ($OpenApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getPost() && $path->getPost()->getSummary() === 'hidden') {
                $OpenApi->getPaths()->addPath($key, $path->withPost(null));
            }
        }
        foreach ($OpenApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getPut() && $path->getPut()->getSummary() === 'hidden') {
                $OpenApi->getPaths()->addPath($key, $path->withPut(null));
            }
        }
        foreach ($OpenApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getDelete() && $path->getDelete()->getSummary() === 'hidden') {
                $OpenApi->getPaths()->addPath($key, $path->withDelete(null));
            }
        }
    }
}