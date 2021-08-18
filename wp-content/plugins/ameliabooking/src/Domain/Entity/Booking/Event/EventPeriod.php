<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Domain\Entity\Booking\Event;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\ValueObjects\DateTime\DateTimeValue;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\Entity\Zoom\ZoomMeeting;

/**
 * Class EventPeriod
 *
 * @package AmeliaBooking\Domain\Entity\Booking\Event
 */
class EventPeriod
{
    /** @var Id */
    private $id;

    /** @var Id */
    private $eventId;

    /** @var  DateTimeValue */
    protected $periodStart;

    /** @var DateTimeValue */
    protected $periodEnd;

    /** @var ZoomMeeting */
    private $zoomMeeting;

    /** @var  Collection */
    protected $bookings;

    /**
     * @return Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Id $id
     */
    public function setId(Id $id)
    {
        $this->id = $id;
    }

    /**
     * @return Id
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param Id $eventId
     */
    public function setEventId(Id $eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return DateTimeValue
     */
    public function getPeriodStart()
    {
        return $this->periodStart;
    }

    /**
     * @param DateTimeValue $periodStart
     */
    public function setPeriodStart(DateTimeValue $periodStart)
    {
        $this->periodStart = $periodStart;
    }

    /**
     * @return DateTimeValue
     */
    public function getPeriodEnd()
    {
        return $this->periodEnd;
    }

    /**
     * @param DateTimeValue $periodEnd
     */
    public function setPeriodEnd(DateTimeValue $periodEnd)
    {
        $this->periodEnd = $periodEnd;
    }

    /**
     * @return ZoomMeeting
     */
    public function getZoomMeeting()
    {
        return $this->zoomMeeting;
    }

    /**
     * @param ZoomMeeting $zoomMeeting
     */
    public function setZoomMeeting(ZoomMeeting $zoomMeeting)
    {
        $this->zoomMeeting = $zoomMeeting;
    }

    /**
     * @return Collection
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * @param Collection $bookings
     */
    public function setBookings(Collection $bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'             => $this->getId() ? $this->getId()->getValue() : null,
            'eventId'        => $this->getEventId() ? $this->getEventId()->getValue() : null,
            'periodStart'    => $this->getPeriodStart()->getValue()->format('Y-m-d H:i:s'),
            'periodEnd'      => $this->getPeriodEnd()->getValue()->format('Y-m-d H:i:s'),
            'zoomMeeting'    => $this->getZoomMeeting() ? $this->getZoomMeeting()->toArray() : null,
            'bookings'       => $this->getBookings() ? $this->getBookings()->toArray() : [],
        ];
    }
}
