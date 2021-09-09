<?php

namespace App\Http\Controllers;

use App\Notifications\SystemReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;

class TestController extends Controller
{
    public function testSlack()
    {
        Notification::route('slack', config('logging.channels.slack.url'))->notify(new SystemReportNotification("Test Deneme"));
    }
}
