<?php

namespace App\Libraries;

use DB;
use GuzzleHttp\Client;
use \Carbon\Carbon as Carbon;

class Helpers
{
    // TODO - refactor - eg getGameSelectArray - specifially the selectArray part
    /**
     * Get Venues
     * @param  boolean $obj Return as Object
     * @return Array|Object
     */
    public static function getVenues($obj = false)
    {
        $venues = \App\EventVenue::all();
        $return = array();
        foreach ($venues as $venue) {
                $return[$venue->id] = $venue->display_name;
        }
        if (!$obj) {
            $return[] = 'None';
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Events
     * @param  string  $order
     * @param  integer $limit
     * @param  boolean $obj   Return as Object
     * @return Array|Object
     */
    public static function getEvents($order = 'DESC', $limit = 0, $obj = false)
    {
        $return = array();
        if ($limit != 0) {
            $events = \App\Event::orderBy('start', $order)->paginate($limit);
        } else {
            $events = \App\Event::orderBy('start', 'DESC')->get();
        }
        $return = array();
        foreach ($events as $event) {
            $return[$event->id] = $event;
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Event Names
     * @param  string  $order
     * @param  integer $limit
     * @param  boolean $future
     * @param  boolean $obj   Return as Object
     * @return Array|Object
     */
    public static function getEventNames($order = 'DESC', $limit = 0, $future = false, $obj = false)
    {
        $return = array();
        if ($limit != 0) {
            if ($future) {
                $events = \App\Event::where('end', '>=', date('Y-m-d'))->orderBy('start', $order)->paginate($limit);
            } else {
                $events = \App\Event::orderBy('start', $order)->paginate($limit);
            }
        } else {
            if ($future) {
                $events = \App\Event::where('end', '>=', date('Y-m-d'))->orderBy('start', 'DESC')->get();
            } else {
                $events = \App\Event::orderBy('start', 'DESC')->get();
            }
        }
        if (!$obj) {
            $return[] = 'None';
        }
        foreach ($events as $event) {
            $return[$event->id] = $event->display_name;
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Total Events Count
     * @return Integer
     */
    public static function getEventTotal()
    {
        $events = \App\Event::count();
        return Settings::getEventCountOffset() + $events;
    }

    // TODO - move to model
    /**
     * Get Next Event Name
     * @return String
     */
    public static function getNextEventName()
    {
        if ($event = \App\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            if ($event->status == 'DRAFT' || $event->status == 'PREVIEW') {
                return $event->display_name . ' - ' . $event->status;
            }
            return $event->display_name;
        }
        return 'Coming soon...';
    }

    /**
     * Get Next Event Slug
     * @return String
     */
    public static function getNextEventSlug()
    {
        if ($event = \App\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return $event->slug;
        }
        return '#';
    }


    /**
     * Get Next Event Description
     * @return String
     */
    public static function getNextEventDesc()
    {
        if ($event = \App\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return $event->desc_long;
        }
        return 'Coming soon...';
    }

    /**
     * Get Next Event Start Date
     * @return String
     */
    public static function getNextEventStartDate()
    {
        if ($event = \App\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return date("d-m-Y H:i", strtotime($event->start));
        }
        return 'Coming soon...';
    }

    /**
     * Get Next Event End Date
     * @return String
     */
    public static function getNextEventEndDate()
    {
        if ($event = \App\Event::where(
            'end',
            '>=',
            Carbon::now()
        )->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
        ) {
            return date("d-m-Y H:i", strtotime($event->end));
        }
        return 'Coming soon...';
    }

    /**
     * Get Total Event Participants Count
     * @return Integer
     */
    public static function getEventParticipantTotal()
    {
        $participants = \App\EventParticipant::count();
        return Settings::getParticipantCountOffset() + $participants;
    }

    /**
     * Get Active Tournaments count for User
     * @param  $event_id
     * @return Integer
     */
    public static function getUserActiveTournaments($event_id)
    {
        $user = \Auth::user();
        $active_tournament_counter = 0;
        foreach ($user->eventParticipants as $event_participant) {
            foreach ($event_participant->tournamentParticipants as $tournament_participant) {
                if ($tournament_participant->eventTournament->event_id == $event_id &&
                    $tournament_participant->eventTournament->status != 'COMPLETE'
                ) {
                    $active_tournament_counter++;
                }
            }
        }
        return $active_tournament_counter;
    }

    /**
     * Format Challonge Rankings
     * @param  $final_rank
     * @return String
     */
    public static function getChallongeRankFormat($final_rank)
    {
        if ($final_rank == '1') {
            return '1st';
        }
        if ($final_rank == '2') {
            return '2nd';
        }
        if ($final_rank == '3') {
            return '3rd';
        }
        if (substr($final_rank, -1) == '1') {
            return $final_rank . 'st';
        }
        if (substr($final_rank, -1) == '2') {
            return $final_rank . 'nd';
        }
        if (substr($final_rank, -1) == '3') {
            return $final_rank . 'rd';
        }
        return $final_rank . 'th';
    }

    /**
     * Get Basket Total
     * @param  $basket
     * @return Integer
     */
    public static function getBasketTotal($basket)
    {
        $return = 0;
        foreach ($basket as $ticket_id => $quantity) {
            $ticket = \App\EventTicket::where('id', $ticket_id)->first();
            $return += ($ticket->price * $quantity);
        }
        return $return;
    }

    /**
     * array_key_exists with regex
     * @param  $pattern
     * @param  $array
     * @return Integer
     */
    public static function pregArrayKeyExists($pattern, $array)
    {
        $keys = array_keys($array);
        return (int) preg_grep($pattern, $keys);
    }

    /**
     * Get Games Select Array
     * @param  $publicOnly
     * @return Array
     */
    public static function getGameSelectArray($publicOnly = true)
    {
        return \App\Game::getGameSelectArray($publicOnly);
    }

    /**
     * Get Shop Categories Select Array
     * @param  $publicOnly
     * @return Array
     */
    public static function getShopCategoriesSelectArray($publicOnly = true)
    {
        return \App\ShopItemCategory::getShopCategoriesSelectArray($publicOnly);
    }

    /**
     * Format Shopping Basket into Readable format
     * @param $itemId
     * @return Boolean
     */
    public static function formatBasket($basket)
    {
        if (array_key_exists('shop', $basket)) {
            $formattedBasket = \App\ShopItem::whereIn('id', array_keys($basket['shop']))->get();
        }
        if (array_key_exists('tickets', $basket)) {
            $formattedBasket = \App\EventTicket::whereIn('id', array_keys($basket['tickets']))->get();
        }
        if (!$formattedBasket) {
            return false;
        }
        $formattedBasket->total = 0;
        $formattedBasket->total_credit = 0;
        $formattedBasket->allow_payment = true;
        $formattedBasket->allow_credit = true;
        foreach ($formattedBasket as $item) {
            if (array_key_exists('shop', $basket)) {
                $item->quantity = $basket['shop'][$item->id];
                $formattedBasket->total += $item->price * $item->quantity;
                $formattedBasket->total_credit += $item->price_credit * $item->quantity;
            } else {
                $item->quantity = $basket['tickets'][$item->id];
                $formattedBasket->total += $item->price * $item->quantity;
                $formattedBasket->total_credit += $item->price_credit * $item->quantity;
            }
            if ($item->price_credit == null) {
                $formattedBasket->allow_credit = false;
            }
            if ($item->price == null) {
                $formattedBasket->allow_payment = false;
            }
        }
        return $formattedBasket;
    }

    /**
     * Get CSS Version Number for Cache Busting
     * @return integer
     */
    public static function getCssVersion()
    {
        return \App\Appearance::getCssVersion();
    }
    
    /**
     * Get Card Expiry Month Dates
     * @return array
     */
    public static function getCardExpiryMonthDates()
    {
        $return = array();
        for ($i=1; $i<=12; $i++) {
            $date = $i;
            // if ($date <= 9) {
            //     $date = '0' . $i;
            // }
            $return[$date] = $date;
        }
        return $return;
    }

    /**
     * Get Card Expiry Year Dates
     * @return array
     */
    public static function getCardExpiryYearDates()
    {
        $return = array();
        for ($i=(int)date('y'); $i<=99; $i++) {
            $date = $i;
            // if ($date <= 9) {
            //     $date = '0' . $i;
            // }
            $return[$date] = $date;
        }
        return $return;
    }

    /**
     * Get Supported Event Tags from Eventula
     * @return array
     */
    public static function getEventulaEventTags()
    {
        $client = new Client();
        try {
            $response = $client->get(config('eventula.url') . '/api/tags/events');
        } catch (\Exception $e) {
            return false;
        }
        return json_decode($response->getBody());
    }
}
