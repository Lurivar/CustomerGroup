<?php

namespace CustomerGroup\Model;

use CustomerGroup\Model\Map\CustomerCustomerGroupTableMap;
use CustomerGroup\Model\Map\CustomerGroupTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Model\CustomerQuery as BaseCustomerQuery;
use Thelia\Model\Map\CustomerTableMap;

/**
 * Custom customer queries.
 * Can be used in two ways:
 *     - As a CustomerQuery replacement.
 *     - By using the provided static methods on an existing query.
 *     This query must extend CustomerQuery or have a join to the customer table.
 */
class CustomerQuery extends BaseCustomerQuery
{
    /**
     * {@inheritdoc}
     * @return CustomerQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CustomerQuery) {
            return $criteria;
        }
        $query = new CustomerQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Filter the query by customer groups.
     *
     * @param ModelCriteria $query The query to filter.
     * @param array $customerGroups An array of customer groups codes.
     * @return ModelCriteria The query.
     */
    public static function addCustomerGroupsFilter(ModelCriteria $query, array $customerGroups)
    {
        if (!is_array($customerGroups) || empty($customerGroups)) {
            return $query;
        }

        $query
            // join to customer_customer_group
            ->addJoin(
                CustomerTableMap::ID,
                CustomerCustomerGroupTableMap::CUSTOMER_ID,
                Criteria::LEFT_JOIN
            )
            // then join to customer_group
            ->addJoin(
                CustomerCustomerGroupTableMap::CUSTOMER_GROUP_ID,
                CustomerGroupTableMap::ID,
                Criteria::LEFT_JOIN
            );

        // build the condition for each group
        $groupConditionsNames = [];
        foreach ($customerGroups as $customerGroup) {
            $conditionName = 'condition_customer_group_' . $customerGroup;

            $query->condition(
                $conditionName,
                CustomerGroupTableMap::CODE . Criteria::EQUAL . "?",
                $customerGroup,
                \PDO::PARAM_STR
            );

            $groupConditionsNames[] = $conditionName;
        }

        // add the group conditions
        if (!empty($groupConditionsNames)) {
            $query->where($groupConditionsNames, Criteria::LOGICAL_OR);
        }

        return $query;
    }

    /**
     * @see \CustomerGroup\Model\CustomerQuery::addCustomerGroupsFilter()
     *
     * @param array $customerGroups
     * @return CustomerQuery
     */
    public function filterByCustomerGroups(array $customerGroups)
    {
        return self::addCustomerGroupsFilter($this, $customerGroups);
    }

    /**
     * @see \CustomerGroup\Model\CustomerQuery::addCustomerGroupsFilter()
     *
     * @param ModelCriteria $query
     * @param string $customerGroup
     * @return ModelCriteria
     */
    public static function addCustomerGroupFilter(ModelCriteria $query, $customerGroup)
    {
        return self::addCustomerGroupsFilter($query, [$customerGroup]);
    }

    /**
     * @see \CustomerGroup\Model\CustomerQuery::addCustomerGroupsFilter()
     *
     * @param string $customerGroup
     * @return CustomerQuery
     */
    public function filterByCustomerGroup($customerGroup)
    {
        return self::addCustomerGroupFilter($this, $customerGroup);
    }
}
