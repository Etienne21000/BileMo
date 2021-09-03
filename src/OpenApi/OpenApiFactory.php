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

        foreach ($OpenApi->getPaths()->getPaths() as $path){
//            dd($path->getGet()->getSummary());
        }
//        dd($OpenApi);
        return $OpenApi;
    }
}