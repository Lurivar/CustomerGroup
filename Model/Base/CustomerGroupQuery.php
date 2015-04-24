<?php

namespace CustomerGroup\Model\Base;

use \Exception;
use \PDO;
use CustomerGroup\Model\CustomerGroup as ChildCustomerGroup;
use CustomerGroup\Model\CustomerGroupI18nQuery as ChildCustomerGroupI18nQuery;
use CustomerGroup\Model\CustomerGroupQuery as ChildCustomerGroupQuery;
use CustomerGroup\Model\Map\CustomerGroupTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer_group' table.
 *
 * The list of customer groups
 *
 * @method     ChildCustomerGroupQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildCustomerGroupQuery orderByIsDefault($order = Criteria::ASC) Order by the is_default column
 * @method     ChildCustomerGroupQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCustomerGroupQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildCustomerGroupQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildCustomerGroupQuery orderByPosition($order = Criteria::ASC) Order by the position column
 *
 * @method     ChildCustomerGroupQuery groupByCode() Group by the code column
 * @method     ChildCustomerGroupQuery groupByIsDefault() Group by the is_default column
 * @method     ChildCustomerGroupQuery groupById() Group by the id column
 * @method     ChildCustomerGroupQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildCustomerGroupQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildCustomerGroupQuery groupByPosition() Group by the position column
 *
 * @method     ChildCustomerGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerGroupQuery leftJoinCustomerCustomerGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerCustomerGroup relation
 * @method     ChildCustomerGroupQuery rightJoinCustomerCustomerGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerCustomerGroup relation
 * @method     ChildCustomerGroupQuery innerJoinCustomerCustomerGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerCustomerGroup relation
 *
 * @method     ChildCustomerGroupQuery leftJoinCustomerGroupI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerGroupI18n relation
 * @method     ChildCustomerGroupQuery rightJoinCustomerGroupI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerGroupI18n relation
 * @method     ChildCustomerGroupQuery innerJoinCustomerGroupI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerGroupI18n relation
 *
 * @method     ChildCustomerGroup findOne(ConnectionInterface $con = null) Return the first ChildCustomerGroup matching the query
 * @method     ChildCustomerGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomerGroup matching the query, or a new ChildCustomerGroup object populated from the query conditions when no match is found
 *
 * @method     ChildCustomerGroup findOneByCode(string $code) Return the first ChildCustomerGroup filtered by the code column
 * @method     ChildCustomerGroup findOneByIsDefault(boolean $is_default) Return the first ChildCustomerGroup filtered by the is_default column
 * @method     ChildCustomerGroup findOneById(int $id) Return the first ChildCustomerGroup filtered by the id column
 * @method     ChildCustomerGroup findOneByCreatedAt(string $created_at) Return the first ChildCustomerGroup filtered by the created_at column
 * @method     ChildCustomerGroup findOneByUpdatedAt(string $updated_at) Return the first ChildCustomerGroup filtered by the updated_at column
 * @method     ChildCustomerGroup findOneByPosition(int $position) Return the first ChildCustomerGroup filtered by the position column
 *
 * @method     array findByCode(string $code) Return ChildCustomerGroup objects filtered by the code column
 * @method     array findByIsDefault(boolean $is_default) Return ChildCustomerGroup objects filtered by the is_default column
 * @method     array findById(int $id) Return ChildCustomerGroup objects filtered by the id column
 * @method     array findByCreatedAt(string $created_at) Return ChildCustomerGroup objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildCustomerGroup objects filtered by the updated_at column
 * @method     array findByPosition(int $position) Return ChildCustomerGroup objects filtered by the position column
 *
 */
abstract class CustomerGroupQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \CustomerGroup\Model\Base\CustomerGroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\CustomerGroup\\Model\\CustomerGroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerGroupQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \CustomerGroup\Model\CustomerGroupQuery) {
            return $criteria;
        }
        $query = new \CustomerGroup\Model\CustomerGroupQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCustomerGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CustomerGroupTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerGroupTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildCustomerGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT CODE, IS_DEFAULT, ID, CREATED_AT, UPDATED_AT, POSITION FROM customer_group WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildCustomerGroup();
            $obj->hydrate($row);
            CustomerGroupTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildCustomerGroup|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomerGroupTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomerGroupTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CustomerGroupTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the is_default column
     *
     * Example usage:
     * <code>
     * $query->filterByIsDefault(true); // WHERE is_default = true
     * $query->filterByIsDefault('yes'); // WHERE is_default = true
     * </code>
     *
     * @param     boolean|string $isDefault The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByIsDefault($isDefault = null, $comparison = null)
    {
        if (is_string($isDefault)) {
            $is_default = in_array(strtolower($isDefault), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CustomerGroupTableMap::IS_DEFAULT, $isDefault, $comparison);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CustomerGroupTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CustomerGroupTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerGroupTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CustomerGroupTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CustomerGroupTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerGroupTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CustomerGroupTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CustomerGroupTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerGroupTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position > 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(CustomerGroupTableMap::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(CustomerGroupTableMap::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerGroupTableMap::POSITION, $position, $comparison);
    }

    /**
     * Filter the query by a related \CustomerGroup\Model\CustomerCustomerGroup object
     *
     * @param \CustomerGroup\Model\CustomerCustomerGroup|ObjectCollection $customerCustomerGroup  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByCustomerCustomerGroup($customerCustomerGroup, $comparison = null)
    {
        if ($customerCustomerGroup instanceof \CustomerGroup\Model\CustomerCustomerGroup) {
            return $this
                ->addUsingAlias(CustomerGroupTableMap::ID, $customerCustomerGroup->getCustomerGroupId(), $comparison);
        } elseif ($customerCustomerGroup instanceof ObjectCollection) {
            return $this
                ->useCustomerCustomerGroupQuery()
                ->filterByPrimaryKeys($customerCustomerGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCustomerCustomerGroup() only accepts arguments of type \CustomerGroup\Model\CustomerCustomerGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerCustomerGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function joinCustomerCustomerGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerCustomerGroup');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CustomerCustomerGroup');
        }

        return $this;
    }

    /**
     * Use the CustomerCustomerGroup relation CustomerCustomerGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerGroup\Model\CustomerCustomerGroupQuery A secondary query class using the current class as primary query
     */
    public function useCustomerCustomerGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomerCustomerGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerCustomerGroup', '\CustomerGroup\Model\CustomerCustomerGroupQuery');
    }

    /**
     * Filter the query by a related \CustomerGroup\Model\CustomerGroupI18n object
     *
     * @param \CustomerGroup\Model\CustomerGroupI18n|ObjectCollection $customerGroupI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByCustomerGroupI18n($customerGroupI18n, $comparison = null)
    {
        if ($customerGroupI18n instanceof \CustomerGroup\Model\CustomerGroupI18n) {
            return $this
                ->addUsingAlias(CustomerGroupTableMap::ID, $customerGroupI18n->getId(), $comparison);
        } elseif ($customerGroupI18n instanceof ObjectCollection) {
            return $this
                ->useCustomerGroupI18nQuery()
                ->filterByPrimaryKeys($customerGroupI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCustomerGroupI18n() only accepts arguments of type \CustomerGroup\Model\CustomerGroupI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerGroupI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function joinCustomerGroupI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerGroupI18n');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CustomerGroupI18n');
        }

        return $this;
    }

    /**
     * Use the CustomerGroupI18n relation CustomerGroupI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CustomerGroup\Model\CustomerGroupI18nQuery A secondary query class using the current class as primary query
     */
    public function useCustomerGroupI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinCustomerGroupI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerGroupI18n', '\CustomerGroup\Model\CustomerGroupI18nQuery');
    }

    /**
     * Filter the query by a related Customer object
     * using the customer_customer_group table as cross reference
     *
     * @param Customer $customer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByCustomer($customer, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCustomerCustomerGroupQuery()
            ->filterByCustomer($customer, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCustomerGroup $customerGroup Object to remove from the list of results
     *
     * @return ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function prune($customerGroup = null)
    {
        if ($customerGroup) {
            $this->addUsingAlias(CustomerGroupTableMap::ID, $customerGroup->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerGroupTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CustomerGroupTableMap::clearInstancePool();
            CustomerGroupTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildCustomerGroup or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildCustomerGroup object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerGroupTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        CustomerGroupTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerGroupTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomerGroupTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CustomerGroupTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomerGroupTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomerGroupTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CustomerGroupTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CustomerGroupTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'CustomerGroupI18n';

        return $this
            ->joinCustomerGroupI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('CustomerGroupI18n');
        $this->with['CustomerGroupI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildCustomerGroupI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerGroupI18n', '\CustomerGroup\Model\CustomerGroupI18nQuery');
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {

        return $this
            ->addUsingAlias(CustomerGroupTableMap::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    ChildCustomerGroupQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(CustomerGroupTableMap::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(CustomerGroupTableMap::RANK_COL));
                break;
            default:
                throw new \Propel\Runtime\Exception\PropelException('ChildCustomerGroupQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return    ChildCustomerGroup
     */
    public function findOneByRank($rank, ConnectionInterface $con = null)
    {

        return $this
            ->filterByRank($rank)
            ->findOne($con);
    }

    /**
     * Returns the list of objects
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($con = null)
    {

        return $this
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param     ConnectionInterface optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerGroupTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CustomerGroupTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     ConnectionInterface optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray(ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CustomerGroupTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CustomerGroupTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return ChildCustomerGroup
     */
    static public function retrieveByRank($rank, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(CustomerGroupTableMap::RANK_COL, $rank);

        return static::create(null, $c)->findOne($con);
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     mixed               $order id => rank pairs
     * @param     ConnectionInterface $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder($order, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getPosition() != $order[$pk]) {
                    $object->setPosition($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (\Propel\Runtime\Exception\PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     ConnectionInterface $con       optional connection
     *
     * @return    array list of sortable objects
     */
    static public function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        if (null === $criteria) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if (Criteria::ASC == $order) {
            $criteria->addAscendingOrderByColumn(CustomerGroupTableMap::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(CustomerGroupTableMap::RANK_COL);
        }

        return ChildCustomerGroupQuery::create(null, $criteria)->find($con);
    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      ConnectionInterface $con Connection to use.
     */
    static public function sortableShiftRank($delta, $first, $last = null, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerGroupTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(CustomerGroupTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(CustomerGroupTableMap::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(CustomerGroupTableMap::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(CustomerGroupTableMap::DATABASE_NAME);
        $valuesCriteria->add(CustomerGroupTableMap::RANK_COL, array('raw' => CustomerGroupTableMap::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
        CustomerGroupTableMap::clearInstancePool();
    }

} // CustomerGroupQuery
