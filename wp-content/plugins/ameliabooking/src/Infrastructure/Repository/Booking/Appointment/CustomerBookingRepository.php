<?php

namespace AmeliaBooking\Infrastructure\Repository\Booking\Appointment;

use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Factory\Booking\Appointment\CustomerBookingFactory;
use AmeliaBooking\Domain\Repository\Booking\Appointment\CustomerBookingRepositoryInterface;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\AbstractRepository;
use AmeliaBooking\Infrastructure\WP\InstallActions\DB\Booking\AppointmentsTable;

/**
 * Class CustomerBookingRepository
 *
 * @package AmeliaBooking\Infrastructure\Repository\Booking\Appointment
 */
class CustomerBookingRepository extends AbstractRepository implements CustomerBookingRepositoryInterface
{

    const FACTORY = CustomerBookingFactory::class;

    /**
     * @param CustomerBooking $entity
     *
     * @return mixed
     * @throws QueryExecutionException
     */
    public function add($entity)
    {
        $data = $entity->toArray();

        $params = [
            ':appointmentId'   => $data['appointmentId'],
            ':customerId'      => $data['customerId'],
            ':status'          => $data['status'],
            ':price'           => $data['price'],
            ':persons'         => $data['persons'],
            ':couponId'        => !empty($data['coupon']) ? $data['coupon']['id'] : null,
            ':token'           => $data['token'],
            ':customFields'    => $data['customFields'] && json_decode($data['customFields']) !== false ?
                $data['customFields'] : null,
            ':info'            => $data['info'],
            ':aggregatedPrice' => $data['aggregatedPrice'] ? 1 : 0,
            ':utcOffset'       => $data['utcOffset'],
            ':packageCustomerServiceId' => !empty($data['packageCustomerService']['id']) ?
                $data['packageCustomerService']['id'] : null,
        ];

        try {
            $statement = $this->connection->prepare(
                "INSERT INTO {$this->table} 
                (
                `appointmentId`,
                `customerId`,
                `status`, 
                `price`, 
                `persons`,
                `couponId`, 
                `token`,
                `customFields`,
                `info`,
                `aggregatedPrice`,
                `utcOffset`,
                `packageCustomerServiceId`
                )
                VALUES (
                :appointmentId, 
                :customerId, 
                :status, 
                :price, 
                :persons,
                :couponId,
                :token,
                :customFields,
                :info,
                :aggregatedPrice,
                :utcOffset,
                :packageCustomerServiceId
                )"
            );

            $res = $statement->execute($params);
            if (!$res) {
                throw new QueryExecutionException('Unable to add data in ' . __CLASS__);
            }

            return $this->connection->lastInsertId();
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to add data in ' . __CLASS__, $e->getCode(), $e);
        }
    }

    /**
     * @param int             $id
     * @param CustomerBooking $entity
     *
     * @return mixed
     * @throws QueryExecutionException
     */
    public function update($id, $entity)
    {
        $data = $entity->toArray();

        $params = [
            ':id'           => $id,
            ':customerId'   => $data['customerId'],
            ':status'       => $data['status'],
            ':persons'      => $data['persons'],
            ':couponId'     => !empty($data['coupon']) ? $data['coupon']['id'] : null,
            ':customFields' => $data['customFields'],
        ];

        try {
            $statement = $this->connection->prepare(
                "UPDATE {$this->table} SET
                `customerId`   = :customerId,
                `status`       = :status,
                `persons`      = :persons,
                `couponId`     = :couponId,
                `customFields` = :customFields
                WHERE id = :id"
            );

            $res = $statement->execute($params);

            if (!$res) {
                throw new QueryExecutionException('Unable to save data in ' . __CLASS__);
            }

            return $res;
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to save data in ' . __CLASS__, $e->getCode(), $e);
        }
    }

    /**
     * @param int             $id
     * @param CustomerBooking $entity
     *
     * @return mixed
     * @throws QueryExecutionException
     */
    public function updatePrice($id, $entity)
    {
        $data = $entity->toArray();

        $params = [
            ':id'           => $id,
            ':price'        => $data['price'],
        ];

        try {
            $statement = $this->connection->prepare(
                "UPDATE {$this->table} SET
                `price`   = :price
                WHERE id = :id"
            );

            $res = $statement->execute($params);

            if (!$res) {
                throw new QueryExecutionException('Unable to save data in ' . __CLASS__);
            }

            return $res;
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to save data in ' . __CLASS__, $e->getCode(), $e);
        }
    }

    /**
     * @param int $id
     * @param int $status
     *
     * @return mixed
     * @throws QueryExecutionException
     */
    public function updateStatusByAppointmentId($id, $status)
    {
        $params = [
            ':appointmentId' => $id,
            ':status'        => $status
        ];

        try {
            $statement = $this->connection->prepare(
                "UPDATE {$this->table} SET
                `status` = :status
                WHERE appointmentId = :appointmentId"
            );

            $res = $statement->execute($params);

            if (!$res) {
                throw new QueryExecutionException('Unable to save data in ' . __CLASS__);
            }

            return $res;
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to save data in ' . __CLASS__, $e->getCode(), $e);
        }
    }

    /**
     * @param int $id
     * @param int $status
     *
     * @return mixed
     * @throws QueryExecutionException
     */
    public function updateStatusById($id, $status)
    {
        $params = [
            ':id'     => $id,
            ':status' => $status
        ];

        try {
            $statement = $this->connection->prepare(
                "UPDATE {$this->table} SET
                `status` = :status
                WHERE id = :id"
            );

            $res = $statement->execute($params);

            if (!$res) {
                throw new QueryExecutionException('Unable to save data in ' . __CLASS__);
            }

            return $res;
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to save data in ' . __CLASS__, $e->getCode(), $e);
        }
    }

    /**
     * Returns an array of Customers Id's who have at least one booking until passed date
     *
     * @param $criteria
     *
     * @return array
     * @throws QueryExecutionException
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     */
    public function getReturningCustomers($criteria)
    {
        $appointmentTable = AppointmentsTable::getTableName();

        $params = [];
        $where = [];

        if ($criteria['dates']) {
            $where[] = "(DATE_FORMAT(a.bookingStart, '%Y-%m-%d') < :bookingFrom)";
            $params[':bookingFrom'] = DateTimeService::getCustomDateTimeInUtc($criteria['dates'][0]);
        }

        $where = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        try {
            $statement = $this->connection->prepare(
                "SELECT 
                customerId,
                COUNT(*) AS occurrences
                FROM {$this->table} cb
                INNER JOIN {$appointmentTable} a ON a.id = cb.appointmentId
                $where
                GROUP BY customerId"
            );

            $statement->execute($params);

            $rows = $statement->fetchAll();
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to return customer bookings from' . __CLASS__, $e->getCode(), $e);
        }

        return $rows;
    }

    /**
     * Returns an array of Customers Id's bookings in selected period
     *
     * @param $criteria
     *
     * @return array
     * @throws QueryExecutionException
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     */
    public function getFilteredDistinctCustomersIds($criteria)
    {
        $appointmentTable = AppointmentsTable::getTableName();

        $params = [];
        $where = [];

        if ($criteria['dates']) {
            $where[] = "(DATE_FORMAT(a.bookingStart, '%Y-%m-%d') BETWEEN :bookingFrom AND :bookingTo)";
            $params[':bookingFrom'] = DateTimeService::getCustomDateTimeInUtc($criteria['dates'][0]);
            $params[':bookingTo'] = DateTimeService::getCustomDateTimeInUtc($criteria['dates'][1]);
        }

        $where = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        try {
            $statement = $this->connection->prepare(
                "SELECT DISTINCT 
                cb.customerId
                FROM {$this->table} cb
                INNER JOIN {$appointmentTable} a ON a.id = cb.appointmentId
                $where"
            );

            $statement->execute($params);

            $rows = $statement->fetchAll();
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to return customer bookings from' . __CLASS__, $e->getCode(), $e);
        }

        return $rows;
    }

    /**
     * Returns token for given id
     *
     * @param $id
     *
     * @return array
     * @throws QueryExecutionException
     */
    public function getToken($id)
    {
        try {
            $statement = $this->connection->prepare(
                "SELECT cb.token
                FROM {$this->table} cb
                WHERE cb.id = :id"
            );

            $statement->execute([':id' => $id]);

            $row = $statement->fetch();
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to return customer booking from' . __CLASS__, $e->getCode(), $e);
        }

        return $row;
    }

    /**
     * @param int    $customerId
     * @param string $info
     *
     * @return mixed
     * @throws QueryExecutionException
     */
    public function updateInfoByCustomerId($customerId, $info)
    {
        $params = [
            ':customerId' => $customerId,
            ':info'       => $info
        ];

        try {
            $statement = $this->connection->prepare(
                "UPDATE {$this->table} SET
                `info` = :info
                WHERE customerId = :customerId"
            );

            $res = $statement->execute($params);

            if (!$res) {
                throw new QueryExecutionException('Unable to save data in ' . __CLASS__);
            }

            return $res;
        } catch (\Exception $e) {
            throw new QueryExecutionException('Unable to save data in ' . __CLASS__, $e->getCode(), $e);
        }
    }
}
