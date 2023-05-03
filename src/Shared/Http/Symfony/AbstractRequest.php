<?php

declare(strict_types=1);

namespace App\Shared\Http\Symfony;

use App\Shared\Domain\RequestInterface;
use App\Shared\Domain\Utils;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequest implements RequestInterface
{
    protected array $headers = [];
    protected array $params = [];

    public function params(): array
    {
        return $this->params;
    }

    public function populate(Request $request): void
    {
        $this->headers = $request->headers->all();

        $data = $request->query->all();

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)) {
            $body = Utils::jsonDecode($request->getContent());

            if (is_array($body)) {
                $data = array_merge($data, $body);
            }
        }

        foreach ($data as $key => $value) {
            $this->params[$key] = $value;

            if (property_exists($this, Utils::toCamelCase($key))) {
                $this->{Utils::toCamelCase($key)} = $value;
            }
        }

        $routeParams = $request->attributes->get('_route_params', null) ?? null;

        if (!is_null($routeParams) && is_array($routeParams)) {
            foreach ($routeParams as $key => $value) {
                $this->params[Utils::toSnakeCase($key)] = $value;

                if (property_exists($this, Utils::toCamelCase($key))) {
                    $this->{Utils::toCamelCase($key)} = $value;
                }
            }
        }
    }
}
