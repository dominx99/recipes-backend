<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Recipes\Application\Match\IncompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\RecipesMatcherComposite;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Tags\Domain\Tag;
use App\Cookery\Tags\Domain\TagRepository;
use App\Shared\Domain\Collection\ArrayCollection;
use App\Shared\Http\Symfony\ApiController;
use Doctrine\Common\Collections\Criteria;

use function Lambdish\Phunctional\apply;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RecipesMatchByIngredientsGetController extends ApiController
{
    public function __construct(
        private RecipeRepository $recipeRepository,
        private TagRepository $tagRepository
    ) {
    }

    #[Route('api/v1/recipes/match-by-tags', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $tags = $request->get('tags') ?? [];

        $matcher = new RecipesMatcherComposite(
            new IncompleteRecipesMatcher(1),
        );

        $tags = $this->tagRepository->matching(
            Criteria::create()->where(
                Criteria::expr()->in('name', $tags)
            )
        );

        $tagNames = new ArrayCollection($tags->map(fn (Tag $tag) => $tag->name())->toArray());

        $recipes = $this->recipeRepository->all();
        $collection = apply($matcher, [$recipes, $tagNames]);

        return $this->respond($collection->toArray());
    }
}
