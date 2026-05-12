<?php

namespace Modules\ZeroPayModule\ValueObjects;

enum NotificationEventType: string
{
    case SessionPending = 'session.pending';
    case SessionOpened = 'session.opened';
    case SessionProcessing = 'session.processing';
    case SessionCompleted = 'session.completed';
    case SessionFailed = 'session.failed';
    case SessionExpired = 'session.expired';
    case Unknown = 'unknown';
}
