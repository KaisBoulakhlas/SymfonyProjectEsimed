<?php
namespace App\Controller;

use App\Repository\AdvertRepository;
use Symfony\Component\HttpFoundation\Request;

final class GetAdvertsPublished
{

    /**
     * @var AdvertRepository
     */
    private AdvertRepository $advertRepository;

    public function __construct(AdvertRepository $advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }

    public function __invoke(Request $request)
    {
        if ($request->get('category_id')){
            return $this->advertRepository->findBy(["state"=>"published","category"=>$request->get('category_id')]);
        }
        return $this->advertRepository->findBy(["state"=>"published"]);
    }

}