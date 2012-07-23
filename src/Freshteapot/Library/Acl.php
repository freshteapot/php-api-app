<?php

namespace Freshteapot\Library;

/**
 * @TODO A test does exist in the old code. Port it.
 * @TODO Consider keeping a list of all acl names
 *
 * @author freshteapot
 *
 */
class Acl
{
    /**
     * Grant the user access to something.
     * Usually we are granting (using 1),
     * Saying via $access what the user can now unlock.
     *
     * @param string $user_id
     * @param string $access
     * @param boolean $allow
     * @return boolean
     */
    function grant($user_id, $access, $allow='1')
    {
        if (empty( $user_id ) || empty( $access )) {
            return false;
        }
        $query="INSERT INTO `acl`VALUES(null,:user_id,:name,:allow,:created);";
        $result = db::insert( $query, array(
            'user_id' => $user_id,
            'name' => $access,
            'allow' => $allow,
            'created' => $_SERVER[ 'REQUEST_DATETIME' ]
        ), 'id' );
        return !empty( $result );
    }

    /**
     * Check the userid has access to $name.
     * This is done by checking for 1.
     *
     * @param string $user_id
     * @param string $name
     */
    function allow( $user_id, $name )
    {
        $db_data = array(
        "query" => "
            SELECT `id`
            FROM `acl`
            WHERE `user_id`=:user_id
            AND `access`=:allow
            AND `allow`=1
        ",
        "query_data" => array(
            "user_id" => $user_id,
            "allow" => $name
        )
        );

        $options = array( 'fetch'=>'', 'fetchmode'=>PDO::FETCH_COLUMN );
        $item = db::get( $db_data, $options );

        return ( !empty( $item ) ) ? true: false;
    }


    /**
     * @param string $user_id
     * @param string $access
     * @return bool
     */
    function revoke( $user_id, $access='' )
    {
        if( $access !== '' )
        {
            if( $this->allow( $user_id, $access ) === true )
            {
                $query="DELETE FROM `acl` WHERE `user_id`=:u AND `access`=:a;";
                $result = db::delete( $query, array(
                    'u' => $user_id,
                    'a' => $access
                ));
            }else{
                $result = false;
            }
        }else{
            $query="DELETE FROM `acl` WHERE `user_id`=:u;";
            $result = db::delete($query, array('u' => $user_id));
        }
        return !empty( $result );
    }
}