<?php

namespace App;

abstract class SubscriberState
{
    const ACTIVE = 'active';
    const UNSUBSCRIBED = 'unsubscribed';
    const JUNK = 'junk';
    const BOUNCED = 'bounced';
    const UNCONFIRMED = 'unconfirmed';
}
