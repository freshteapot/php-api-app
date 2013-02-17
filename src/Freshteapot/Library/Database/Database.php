<?php
namespace Freshteapot\Library\Database;

/*
 This should change - or something, maybe cache it ( with the variables ) but before the connection
 //#fix $db_data can include cache parameters
 */
class Database
{
    private static $cache = array();
    private static $dbh = array();
    private static $config = array();
    private static $instance = null;

    /**
     * 
     * Enter description here ...
     * @param unknown_type $type
     */
    private static function load($type)
    {
        //$config = false;
        //include PATH_TO_APP . 'config/db.php';
        try{
            self::$cache = false;
            self::$config['database'] = self::$config['db_name'];
            self::$dbh[$type]=new \PDO( self::$config['type'] . ':host=' . self::$config['host'] . ';dbname=' . self::$config['db_name'], self::$config['user'][$type], self::$config['password'][$type] );
            self::$dbh[$type]->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            //          self::$dbh[$type]->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true );
            self::$dbh[$type]->exec( "SET NAMES 'utf8'");
            //set to utc. this should be done on the server!
        } catch( \PDOException $e )
        {
            echo $e->getMessage();
        }

    }

    static public function init( $config )
    {
        self::$config = $config;
        self::load('r');
    }

    public static function close()
    {
        self::$dbh[ 'r' ] = null;
        self::$dbh[ 'w' ] = null;
    }

    public static function writer( )
    {
        if( !isset( self::$dbh[ 'w' ] ) )
        {
            self::load( 'w' );
        }
        //      echo 'Using Writer';
        //      self::$dbh['w']->exec( "SET GLOBAL time_zone ='UTC'" );
        return self::$dbh['w'];
    }

    //This would be pretty sweet if I could have this sit on top of almost all calls.
    /*
     Would need multiple users - not a problem really.
     Drop it in the config
     $config['writer']
     $config['reader']

     what else
     */
    public static function reader()
    {
        if( !isset( self::$dbh[ 'r' ] ) )
        {
            self::load( 'r' );
        }
        //      echo 'Using Reader';
        return self::$dbh['r'];
    }



    public static function exec( $query )
    {
        try
        {
            self::writer()->exec( $query );
            return true;
        }catch( \PDOException $e ){
            return false;
        }
    }



    public static function exists( $data )
    {
        try
        {
            $options = array( 'fetch'=>'', 'fetchmode' => \PDO::FETCH_COLUMN );
            $result = self::get( $data, $options );

            if( $result === '1' )
            {
                return new response( true, $data[ 'query_data' ] );
            }else{
                return new response( false, false );
            }
        }catch( \PDOException $e ){
            if( $e->getCode() === 'HY093' )
            {
                die( "Missing variable" );
            }
            return false;
        }
    }



    /*
     return false or data
     */
    public static function get( $data, $opt=array() )
    {
        $options = array
        (
            'fetchmode'=>\PDO::FETCH_OBJ,
/*
 * @todo - what happens if we change this to FETCH_ASSOC
 */     
//          'fetchmode'=>\PDO::FETCH_ASSOC,
            'fetch'=>'all',
            'use'=>'reader',
            'store'=>false,
            'cache' => false,
            'return.as' => ''
        );
        if( self::$cache !== false )
        {
            ( ( isset( $opt['cache'] ) ) ? $options['cache'] = $opt['cache']  :'');
            $resposne = self::$cache->do( $options[ 'cache' ] );
            //              ->setType( $options[ 'cache' ][ 'type' ] )
            //              ->get( $options[ 'cache' ][ 'key' ] );
            if( $response->code === true )
            {
                return $response->message;
            }
        }

        ( ( isset( $opt['use'] ) ) ? $options['use'] = $opt['use']  :'');
        ( ( isset( $opt['fetch'] ) ) ? $options['fetch'] = $opt['fetch']  :'');
        ( ( isset( $opt['fetchmode'] ) ) ? $options['fetchmode'] = $opt['fetchmode']  :'');
        ( ( isset( $opt['return.as'] ) ) ? $options['return.as'] = $opt['return.as']  :'');

        $options['fetch'] = 'fetch' . ( ( !empty( $options['fetch'] ) ) ? ucfirst( strtolower( $options['fetch'] ) ) : '' );
        if( $options['use'] !== 'reader' && $options['use'] !== 'writer' ) die( "db needs to be reader or writer" );
        //Tidy up the sql a little
        //Do not do anything more!!!
        $query = trim( $data['query'] );

        if( empty( $query ) )return;
        try
        {
            $fetch = $options['fetch'];
            $use = $options['use'];

            $db = self::$use();
            $s = $db->prepare( $query );

            if( isset( $data['query_data'] ) && is_array( $data['query_data'] ) )
            {
                self::bindData( $s, $query, $data['query_data'] );
            }

            $s->execute();
            //If the column doesn't exist. Due to my debug settings, this doesn't get  displayed.
      $result = $s->$fetch( $options['fetchmode'] );
      $s->closeCursor();

      if( self::$cache !== false && $options[ 'cache' ] !== '' && isset( $options[ 'cache' ][ 'save' ] ) )
      {
        self::$cache->do( $options[ 'cache' ], $result );
      }
      //http://no.php.net/manual/en/pdostatement.fetch.php
      //The return value of this function on success depends on the fetch type. In all cases, FALSE is returned on failure.
      //array sends back an empty array.
      //            return $result;

      //                                      ftp_debug( var_export( $result, true ), 'Actual Result', true, false );
      if( !empty( $options[ 'return.as' ] ) )
      {
        //                                      ftp_debug( $options, '', true, false );
        switch( $options[ 'return.as' ] )
        {
            case "int":
                //                                          ftp_debug( var_export( $result, true ), 'in int', true, false );
                $result = (int)$result;
                //                                          ftp_debug( var_export( $result, true ), 'in int after', true, false );
                break;
            case "boolean":
                //                                          ftp_debug( var_export( $result, true ), 'in int', true, false );
                $result = (bool)$result;
                //                                          ftp_debug( var_export( $result, true ), 'in int after', true, false );
                break;
            case "string":
                //                                          ftp_debug( var_export( $result, true ), 'in int', true, false );
                $result = (string)$result;
                //                                          ftp_debug( var_export( $result, true ), 'in int after', true, false );
                break;
            default:
                die( "return.as option doesnt exist. You know what to do. Are you read?" );
                break;
        }
        return $result;

      }else{

        if( !empty( $result ) )
        {
            return $result;
        }
        return false;
      }
      //#fix not a major issue at the moment...
      //Ie it could leak data.
      //The entire error handling is broken
        }catch( \PDOException $e ){
            if( $e->getCode() === 'HY093' )
            {
                die( "Missing variable" );
            }else if( $e->getCode() === '42S22' )
            {
                print_r($e);
                ftp_debug( 'sds', $e->getMessage() );
            }
            return false;
        }
    }


    /*
     * @todo - $cacheKey doesnt exist yet.
     */
    public static function insert( $query, $data='', $primaryKey='',$cacheKey='' )
    {
        try
        {
            $id = true;
            
            $s = self::writer()->prepare( $query );

            if( is_array( $data ) )
            {
                self::bindData( $s, $query, $data );
            }
            $s->execute();
            $s->closeCursor();
            
            if ( !empty( $primaryKey ) )
            {
                $id = self::writer()->lastInsertId();
            }

            if (!empty($cacheKey))
            {
                /*
                 * @todo delete cache of this database record
                 */
            }
            return $id;
        }catch( \PDOException $e ){
            if( $e->getCode() === 'HY093' )
            {
                die( "Missing variable" );
            }
            print_r($e);
            return false;
        }
    }



    public static function delete( $query, $data='' )
    {
        try
        {
            $s = self::writer()->prepare( $query );
            if( is_array( $data ) )
            {
                self::bindData( $s, $query, $data );
            }
            $s->execute();
            $s->closeCursor();
            return true;
        }catch( \PDOException $e ){
            if( $e->getCode() === 'HY093' )
            {
                die( "Missing variable" );
            }
            return false;
        }
    }



    public static function update( $query, $data='', $confirm=false )
    {
        if( $confirm )
        {

        }
        try
        {
            $s = self::writer()->prepare( $query );
            if( is_array( $data ) )
            {
                self::bindData( $s, $query, $data );
            }
            $s->execute();
            $s->closeCursor();
            return true;
        }catch( \PDOException $e ){
            if( $e->getCode() === 'HY093' )
            {
                die( "Missing variable" );
            }
            return false;
        }
    }



    private static function bindData( $pdo_statement, $query, $data )
    {
        $found = array();

        foreach( $data as $k => $v )
        {
            $key = ":". $k;
            if( isset( $found[$key] ) )
            {
                continue;
            }
            $found[$key] = true;
            if( strpos( $query, $key ) === false )
            {
                $data = array( "query" => $query, "params" => $data, "currentKey" => $key );
                error_log(print_r($data, true), 3, "/tmp/error.db.log");
                //@todo throw an exception
                throw Exception(__METHOD__ . "Please check missing data for the query. Shouldn't happen in live.");
                exit;
            }
            $pdo_statement->bindValue($key, $v );
        }
        /*
         foreach( $data as $k => $v )
         {
         $key = ":". $k;
         if( isset( $found[$key] ) || strpos( $query, $key ) !== false )
         {
         if( !isset( $found[$key] ) )
         {
         $found[$key] = true;
         }
         $pdo_statement->bindValue($key, $v );
         }
         }
         */
        return $pdo_statement;
    }

    public static function getConfig( $name )
    {
        if( isset(self::$config[$name]) )
        {
            return self::$config[$name];
        }
        return -1;
    }
    //much work to do.
    public function initCache()
    {
        $this->cache = new cache();
        //setType = apc / memcahce
        /*
         [ 'cache' ]array(
         'expire'
         'key'
         'keyring'
         'type'
         'save'
         );
         [ 'cache' ]array(
         'key'
         'type'
         );
         [ 'cache' ]array(
         'key'
         );
         function do( $options )
         {
         $this->reset();
         $this->setType( $options[ 'type' ] );
         $this->setExpiry( $options[ 'expiry' ] );
         $this->setKey( $options[ 'key' ] );
         $data = func_get_arg( '1' );
         $this->set
         }
         */
    }
}//end the db class