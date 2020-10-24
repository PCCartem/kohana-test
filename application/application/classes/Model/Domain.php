<?php

/**
 * Class Model_User
 *
 * @property string $name
 * @property string $city
 * @property string $url
 *
 */
class Model_Domain extends Model
{
    protected $_table_name = 'user_requests';

    /**
     * @throws Kohana_Exception
     */
    public function run_counter()
    {
        $domains = DB::select(DB::expr('COUNT("id") as count'), 'domain')->from('users')
            ->group_by( 'domain')
            ->execute()
            ->as_array();;

        foreach ($domains as $domain) {
            DB::insert('domains', array('domain', 'count_users'))->values([$domain['domain'], $domain['count']])->execute();
        }

    }

    /**
     * @return array
     */
    public function all()
    {
        return DB::select()->from('users')->execute()->as_array();
    }
}
