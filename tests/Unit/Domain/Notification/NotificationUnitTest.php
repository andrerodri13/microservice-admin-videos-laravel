<?php

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{


    public function testGetErrors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddErrors()
    {
        $notification = new Notification();
        $notification->addErrors([
            'context' => 'video',
            'message' => 'Video title is required',
        ]);

        $errors = $notification->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testHasErrors()
    {
        $notification = new Notification();
        $this->assertFalse($notification->hasErrors());

        $notification->addErrors([
            'context' => 'video',
            'message' => 'Video title is required',
        ]);

        $this->assertTrue($notification->hasErrors());
    }

    public function testMessage()
    {
        $notification = new Notification();
        $notification->addErrors([
            'context' => 'video',
            'message' => 'Video title is required',
        ]);

        $notification->addErrors([
            'context' => 'video',
            'message' => 'Video description is required',
        ]);
        $message = $notification->messages();

        $this->assertIsString($message);
        $this->assertEquals(
            expected: "video: Video title is required,video: Video description is required,",
            actual: $message
        );
    }

    public function testMessageFilterContext()
    {
        $notification = new Notification();
        $notification->addErrors([
            'context' => 'video',
            'message' => 'Video title is required',
        ]);

        $notification->addErrors([
            'context' => 'category',
            'message' => 'name is required',
        ]);

        $this->assertCount(2, $notification->getErrors());

        $message = $notification->messages(
            context: 'video'
        );

        $this->assertIsString($message);
        $this->assertEquals(
            expected: "video: Video title is required,",
            actual: $message
        );
    }
}
