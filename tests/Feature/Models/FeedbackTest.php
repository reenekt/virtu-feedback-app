<?php

namespace Tests\Feature\Models;

use App\Models\Feedback;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test list of feedbacks
     *
     * @return void
     */
    public function test_feedback_list_endpoint_returns_paginated_items()
    {
        Feedback::factory()->count(2)->create();

        $response = $this->getJson('/api/feedback');

        $response->assertOk();

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has(
                    'data',
                    2,
                    fn(AssertableJson $json) => $json->hasAll(['fullname', 'contact_phone'])->etc()
                )
                ->hasAll(['meta', 'links'])
        );
    }

    public function test_show_feedback_endpoint_returns_feedback()
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $response = $this->getJson('/api/feedback/' . $feedback->id);

        $response->assertOk();

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where('data.fullname', $feedback->fullname)
                ->where('data.contact_phone', $feedback->contact_phone)
                ->etc()
        );
    }

    public function test_create_feedback_request_with_valid_data_creates_feedback()
    {
        $data = [
            'fullname' => 'John Joe',
            'contact_phone' => '+79991234567',
        ];

        $response = $this->postJson('/api/feedback', $data);

        $response->assertCreated();

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where('success', true)
                ->where('model.fullname', $data['fullname'])
                ->where('model.contact_phone', $data['contact_phone'])
        );
    }

    public function test_create_feedback_request_with_valid_data_and_attachment_creates_feedback_and_stores_file()
    {
        Storage::fake('local');

        $attachment = UploadedFile::fake()->image('attachment_file_john_joe.png');
        $expectedAttachmentPath = 'feedback/attachments/' . $attachment->hashName();
        $data = [
            'fullname' => 'John Joe',
            'contact_phone' => '+79991234567',
            'attachment' => $attachment
        ];

        $response = $this->postJson('/api/feedback', $data);

        $response->assertCreated();

        Storage::assertExists($expectedAttachmentPath);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where('success', true)
                ->where('model.fullname', $data['fullname'])
                ->where('model.contact_phone', $data['contact_phone'])
                ->where('model.attachment_file', $expectedAttachmentPath)
        );
    }

    public function test_create_feedback_request_with_wrong_phone_fails_validation()
    {
        $data = [
            'fullname' => 'John Joe',
            'contact_phone' => 'some_wrong_phone',
        ];

        $response = $this->postJson('/api/feedback', $data);

        $response->assertUnprocessable();
    }

    public function test_create_feedback_request_with_wrong_fullname_fails_validation()
    {
        $data = [
            'fullname' => 'a',
            'contact_phone' => '+79991234567',
        ];

        $response = $this->postJson('/api/feedback', $data);

        $response->assertUnprocessable();
    }

    public function test_create_feedback_request_with_empty_data_fails_validation()
    {
        $data = [
            'fullname' => null,
        ];

        $response = $this->postJson('/api/feedback', $data);

        $response->assertUnprocessable();
    }

    public function test_update_feedback_request_with_valid_data_updates_feedback()
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $data = [
            'fullname' => 'John Joe',
            'contact_phone' => '+79991234567',
        ];

        $response = $this->putJson('/api/feedback/' . $feedback->id, $data);

        $response->assertOk();

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where('success', true)
                ->where('model.fullname', $data['fullname'])
                ->where('model.contact_phone', $data['contact_phone'])
        );
    }

    public function test_update_feedback_request_with_valid_dataand_attachment_updates_feedback_and_stores_file()
    {
        Storage::fake('local');

        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $attachment = UploadedFile::fake()->image('attachment_file_john_joe.png');
        $expectedAttachmentPath = 'feedback/attachments/' . $attachment->hashName();
        $data = [
            'fullname' => 'John Joe',
            'contact_phone' => '+79991234567',
            'attachment' => $attachment
        ];

        $response = $this->putJson('/api/feedback/' . $feedback->id, $data);

        $response->assertOk();

        Storage::assertExists($expectedAttachmentPath);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where('success', true)
                ->where('model.fullname', $data['fullname'])
                ->where('model.contact_phone', $data['contact_phone'])
                ->where('model.attachment_file', $expectedAttachmentPath)
        );
    }

    public function test_update_feedback_request_with_wrong_phone_fails_validation()
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $data = [
            'fullname' => 'John Joe',
            'contact_phone' => 'some_wrong_phone',
        ];

        $response = $this->putJson('/api/feedback/' . $feedback->id, $data);

        $response->assertUnprocessable();
    }

    public function test_update_feedback_request_with_wrong_fullname_fails_validation()
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $data = [
            'fullname' => null,
        ];

        $response = $this->putJson('/api/feedback/' . $feedback->id, $data);

        $response->assertUnprocessable();
    }

    public function test_update_feedback_request_with_empty_data_fails_validation()
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $data = [
            'fullname' => 'a',
            'contact_phone' => '+79991234567',
        ];

        $response = $this->putJson('/api/feedback/' . $feedback->id, $data);

        $response->assertUnprocessable();
    }

    public function test_delete_feedback_request_makes_soft_deletion_of_model()
    {
        /** @var Feedback $feedback */
        $feedback = Feedback::factory()->create();

        $response = $this->deleteJson('/api/feedback/' . $feedback->id);

        $response->assertNoContent();

        $this->assertDatabaseHas($feedback->getTable(), $feedback->only('id'));

        $response = $this->getJson('/api/feedback/' . $feedback->id);

        $response->assertNotFound();
    }
}
