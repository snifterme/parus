<?php

namespace rokorolov\parus\user\repositories;

use rokorolov\parus\user\models\User;
use rokorolov\parus\user\models\Profile;
use rokorolov\parus\user\dto\UserDto;
use rokorolov\parus\user\dto\UserProfileDto;
use rokorolov\parus\user\dto\UserSafeDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use yii\db\Query;

/**
 * UserReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_USER = 'u';
    const TABLE_SELECT_PREFIX_PROFILE = 'up';

    public $softDelete = true;
    public $softDeleteAttribute = 'deleted_at';
    public $safe = false;
    
    /**
     * Find by id
     *
     * @param int $id
     * @return
     */
    public function findById($id, array $with = [])
    {
        $this->applyRelations($with);
        
        return $this->findFirstBy('u.id', $id);
    }
    
    public function findByUsernameOrEmail($attribute, array $with = [])
    {
        $this->applyRelations($with);
        
        if (!filter_var($attribute, FILTER_VALIDATE_EMAIL) === false) {
            return $this->findFirstBy('email', $attribute);
        } else {
            return $this->findFirstBy('username', $attribute);
        }
    }
    
    public function make()
    {
        if (null === $this->query) {
            $query = (new Query())->from(User::tableName() . ' u');
            
            if ($this->softDelete) {
                $query->andWhere(['u.' . $this->softDeleteAttribute => null]);
            }
            $this->query = $query;
        }
        return $this->query;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->safe === false ? $this->toUserDto($data, $prefix) : $this->toUserSafeDto($data, $prefix);
    }

    protected function getRelations()
    {
        return [
            'profile' => self::RELATION_ONE,
        ];
    }
    
    public function resolveProfile($query)
    {
        $query->addSelect($this->selectProfileAttributesMap())
            ->leftJoin(Profile::tableName() . ' up', 'up.user_id = u.id');
    }

    public function populateProfile($user, $data)
    {
        $user->profile = $this->toProfileDto($data);
    }

    public function selectAttributesMap()
    {
        return 'u.id AS u_id, u.username AS u_username, u.email AS u_email, u.role AS u_role, u.status AS u_status, u.auth_key AS u_auth_key,'
            . ' u.password_hash AS u_password_hash, u.password_reset_token AS u_password_reset_token, u.created_at AS u_created_at,'
            . ' u.updated_at AS u_updated_at';
    }

    public function selectProfileAttributesMap()
    {
        return 'up.user_id AS up_user_id, up.name AS up_name, up.surname AS up_surname, up.language AS up_language,'
            . ' up.avatar_url AS up_avatar_url, up.last_login_on AS up_last_login_on, up.last_login_ip AS up_last_login_ip';
    }

    public function toUserDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_USER : null;
        return new UserDto($data, $prefix);
    }

    public function toProfileDto($data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_PROFILE : null;
        return new UserProfileDto($data, $prefix);
    }

    public function toUserSafeDto($data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_USER : null;
        return new UserSafeDto($data, $prefix);
    }
}
