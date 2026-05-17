<?php

namespace Tests\Unit;

use App\Events\GroupDeleted;
use App\Models\Group;
use App\Models\OtpLog;
use App\Models\TwoFAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\ModelTestCase;

/**
 * OtpLogModelTest test class
 */
#[CoversClass(OtpLog::class)]
class OtpLogModelTest extends ModelTestCase
{
    #[Test]
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new OtpLog,
            [   // fillable
                'requester_id',
                'requester_name',
                'requester_email',
                'owner_id',
                'owner_name',
                'owner_email',
                'twofaccount_id',
                'twofaccount_account',
                'twofaccount_service',
                'ip_address',
                'generated_at',
                'otp_type',
                'counter',
            ],
            [], // hidden
            ['*'], // guarded
            [], // visible
            [   // casts
                'generated_at' => 'datetime',
                'id'           => 'int'
            ],
            [], // dispatechesEvents
            [], // dates
        );
    }

    #[Test]
    public function test_requester_relation()
    {
        $model    = new OtpLog();
        $relation = $model->requester();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('requester_id', $relation->getForeignKeyName());
    }

    #[Test]
    public function test_owner_relation()
    {
        $model    = new OtpLog();
        $relation = $model->owner();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('owner_id', $relation->getForeignKeyName());
    }

    #[Test]
    public function test_twofaccount_relation()
    {
        $model    = new OtpLog();
        $relation = $model->twofaccount();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('twofaccount_id', $relation->getForeignKeyName());
    }
}
