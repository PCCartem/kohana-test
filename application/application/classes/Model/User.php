<?php

/**
 * Class Model_User
 *
 * @property string $name
 * @property string $city
 * @property string $domain
 * @property string $path
 *
 */
class Model_User extends ORM
{
    protected $_table_name = 'users';
    protected $data = [];

    /**
     * @return null|ORM
     * @throws Kohana_Exception
     */
    public function all_users()
    {
        $users = $this->find_all();

        if ($users !== null) {
            foreach ($users->as_array() as $key => $user) {
                $users[$key] = $user->as_array();
            }

            return $users;
        }
        return null;
    }

    /**
     * @param $id
     * @return array|null
     * @throws Kohana_Exception
     */
    public function one_user($id)
    {
        $user = $this->where('id', '=', $id)->find();

        if ($user !== null) {
            return $user->as_array();
        }

        return null;
    }

    /**
     * @param $name
     * @param $city
     * @param $domain
     * @param $path
     * @return ORM
     */
    public function new_user($name, $city, $domain, $path) : ORM
    {
        $this->name = $name;
        $this->city = $city;
        $this->domain = $domain;
        $this->path = $path;

        return $this->save();
    }

    /**
     * @param $id
     * @param $name
     * @param $city
     * @param $domain
     * @param $path
     * @return false|ORM
     * @throws Kohana_Exception
     */
    public function update_user($id, $name, $city, $domain, $path)
    {
        $this->clear();
        $user = $this->where('id', '=', $id)->find();

        if ( $user->as_array()['id'] !== null) {
            $user->name = $name;
            $user->city = $city;
            $user->domain = $domain;
            $user->path = $path;

            return $user->save();
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws Kohana_Exception
     */
    public function remove_user($id)
    {
        $user = $this->where('id', '=', $id)->find();

        if ( $user->as_array()['id'] !== null) {
            $user->delete();

            return true;
        }

        return false;
    }
}
