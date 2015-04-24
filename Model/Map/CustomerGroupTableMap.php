<?php

namespace CustomerGroup\Model\Map;

use CustomerGroup\Model\CustomerGroup;
use CustomerGroup\Model\CustomerGroupQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'customer_group' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CustomerGroupTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'CustomerGroup.Model.Map.CustomerGroupTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'customer_group';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\CustomerGroup\\Model\\CustomerGroup';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'CustomerGroup.Model.CustomerGroup';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the CODE field
     */
    const CODE = 'customer_group.CODE';

    /**
     * the column name for the IS_DEFAULT field
     */
    const IS_DEFAULT = 'customer_group.IS_DEFAULT';

    /**
     * the column name for the ID field
     */
    const ID = 'customer_group.ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'customer_group.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'customer_group.UPDATED_AT';

    /**
     * the column name for the POSITION field
     */
    const POSITION = 'customer_group.POSITION';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    // i18n behavior

    /**
     * The default locale to use for translations.
     *
     * @var string
     */
    const DEFAULT_LOCALE = 'en_US';

    // sortable behavior
    /**
     * rank column
     */
    const RANK_COL = "customer_group.POSITION";


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Code', 'IsDefault', 'Id', 'CreatedAt', 'UpdatedAt', 'Position', ),
        self::TYPE_STUDLYPHPNAME => array('code', 'isDefault', 'id', 'createdAt', 'updatedAt', 'position', ),
        self::TYPE_COLNAME       => array(CustomerGroupTableMap::CODE, CustomerGroupTableMap::IS_DEFAULT, CustomerGroupTableMap::ID, CustomerGroupTableMap::CREATED_AT, CustomerGroupTableMap::UPDATED_AT, CustomerGroupTableMap::POSITION, ),
        self::TYPE_RAW_COLNAME   => array('CODE', 'IS_DEFAULT', 'ID', 'CREATED_AT', 'UPDATED_AT', 'POSITION', ),
        self::TYPE_FIELDNAME     => array('code', 'is_default', 'id', 'created_at', 'updated_at', 'position', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Code' => 0, 'IsDefault' => 1, 'Id' => 2, 'CreatedAt' => 3, 'UpdatedAt' => 4, 'Position' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('code' => 0, 'isDefault' => 1, 'id' => 2, 'createdAt' => 3, 'updatedAt' => 4, 'position' => 5, ),
        self::TYPE_COLNAME       => array(CustomerGroupTableMap::CODE => 0, CustomerGroupTableMap::IS_DEFAULT => 1, CustomerGroupTableMap::ID => 2, CustomerGroupTableMap::CREATED_AT => 3, CustomerGroupTableMap::UPDATED_AT => 4, CustomerGroupTableMap::POSITION => 5, ),
        self::TYPE_RAW_COLNAME   => array('CODE' => 0, 'IS_DEFAULT' => 1, 'ID' => 2, 'CREATED_AT' => 3, 'UPDATED_AT' => 4, 'POSITION' => 5, ),
        self::TYPE_FIELDNAME     => array('code' => 0, 'is_default' => 1, 'id' => 2, 'created_at' => 3, 'updated_at' => 4, 'position' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('customer_group');
        $this->setPhpName('CustomerGroup');
        $this->setClassName('\\CustomerGroup\\Model\\CustomerGroup');
        $this->setPackage('CustomerGroup.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addColumn('CODE', 'Code', 'VARCHAR', true, 45, null);
        $this->addColumn('IS_DEFAULT', 'IsDefault', 'BOOLEAN', true, 1, false);
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('POSITION', 'Position', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CustomerCustomerGroup', '\\CustomerGroup\\Model\\CustomerCustomerGroup', RelationMap::ONE_TO_MANY, array('id' => 'customer_group_id', ), 'CASCADE', 'RESTRICT', 'CustomerCustomerGroups');
        $this->addRelation('CustomerGroupI18n', '\\CustomerGroup\\Model\\CustomerGroupI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'CustomerGroupI18ns');
        $this->addRelation('Customer', '\\Thelia\\Model\\Customer', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'RESTRICT', 'Customers');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'auto_add_pk' => array('name' => 'id', 'autoIncrement' => 'true', 'type' => 'INTEGER', ),
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
            'i18n' => array('i18n_table' => '%TABLE%_i18n', 'i18n_phpname' => '%PHPNAME%I18n', 'i18n_columns' => 'title, description', 'locale_column' => 'locale', 'locale_length' => '5', 'default_locale' => '', 'locale_alias' => '', ),
            'sortable' => array('rank_column' => 'position', 'use_scope' => 'false', 'scope_column' => '', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to customer_group     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                CustomerCustomerGroupTableMap::clearInstancePool();
                CustomerGroupI18nTableMap::clearInstancePool();
            }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 2 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? CustomerGroupTableMap::CLASS_DEFAULT : CustomerGroupTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (CustomerGroup object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CustomerGroupTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CustomerGroupTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CustomerGroupTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CustomerGroupTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CustomerGroupTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = CustomerGroupTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CustomerGroupTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CustomerGroupTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CustomerGroupTableMap::CODE);
            $criteria->addSelectColumn(CustomerGroupTableMap::IS_DEFAULT);
            $criteria->addSelectColumn(CustomerGroupTableMap::ID);
            $criteria->addSelectColumn(CustomerGroupTableMap::CREATED_AT);
            $criteria->addSelectColumn(CustomerGroupTableMap::UPDATED_AT);
            $criteria->addSelectColumn(CustomerGroupTableMap::POSITION);
        } else {
            $criteria->addSelectColumn($alias . '.CODE');
            $criteria->addSelectColumn($alias . '.IS_DEFAULT');
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.POSITION');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(CustomerGroupTableMap::DATABASE_NAME)->getTable(CustomerGroupTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(CustomerGroupTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(CustomerGroupTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new CustomerGroupTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a CustomerGroup or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CustomerGroup object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \CustomerGroup\Model\CustomerGroup) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CustomerGroupTableMap::DATABASE_NAME);
            $criteria->add(CustomerGroupTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = CustomerGroupQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { CustomerGroupTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { CustomerGroupTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the customer_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CustomerGroupQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CustomerGroup or Criteria object.
     *
     * @param mixed               $criteria Criteria or CustomerGroup object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CustomerGroup object
        }

        if ($criteria->containsKey(CustomerGroupTableMap::ID) && $criteria->keyContainsValue(CustomerGroupTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CustomerGroupTableMap::ID.')');
        }


        // Set the correct dbName
        $query = CustomerGroupQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // CustomerGroupTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CustomerGroupTableMap::buildTableMap();
