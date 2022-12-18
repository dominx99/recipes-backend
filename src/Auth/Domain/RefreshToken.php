<?php

declare(strict_types=1);

namespace App\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[ORM\Entity()]
class RefreshToken extends BaseRefreshToken
{
}
