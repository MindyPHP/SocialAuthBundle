<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Model;

use Mindy\Bundle\UserBundle\Model\User;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;

/**
 * Class SocialUser
 *
 * @property string $provider
 * @property string $params
 * @property User $user
 * @property int $user_id
 * @property string|int $social_id
 */
class SocialUser extends Model
{
    public static function getFields()
    {
        return [
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => User::class,
                'null' => true,
                'verboseName' => 'Пользователь',
            ],
            'provider' => [
                'class' => CharField::class,
                'verboseName' => 'Провайдер соц. авторизации',
            ],
            'owner_id' => [
                'class' => CharField::class,
                'verboseName' => 'Идентификатор пользователя в соц. сети',
            ],
            'params' => [
                'class' => TextField::class,
                'verboseName' => 'Параметры',
            ],
        ];
    }

    public function __toString()
    {
        return sprintf('%s %s', $this->provider, $this->user_id);
    }
}
