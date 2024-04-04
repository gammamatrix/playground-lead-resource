<?php

declare(strict_types=1);
/**
 * Playground
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_regions', function (Blueprint $table) {

            // Primary key

            $table->uuid('id')->primary();

            // IDs

            $table->uuid('created_by_id')->nullable()->index();
            $table->uuid('modified_by_id')->nullable()->index();
            $table->uuid('owned_by_id')->nullable()->index();
            $table->uuid('parent_id')->nullable()->index();
            $table->string('model_type')->nullable()->index();
            $table->uuid('campaign_id')->nullable()->index();
            $table->uuid('goal_id')->nullable()->index();
            $table->uuid('lead_id')->nullable()->index();
            $table->uuid('opportunity_id')->nullable()->index();
            $table->uuid('plan_id')->nullable()->index();
            $table->uuid('region_id')->nullable()->index();
            $table->uuid('report_id')->nullable()->index();
            $table->uuid('source_id')->nullable()->index();
            $table->uuid('task_id')->nullable()->index();
            $table->uuid('team_id')->nullable()->index();
            $table->uuid('teammate_id')->nullable()->index();

            // Dates

            $table->timestamps();

            $table->softDeletes();

            $table->dateTime('start_at')->nullable()->index();
            $table->dateTime('planned_start_at')->nullable();
            $table->dateTime('end_at')->nullable()->index();
            $table->dateTime('planned_end_at')->nullable();
            $table->dateTime('calculated_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->dateTime('closed_at')->nullable()->index();
            $table->dateTime('embargo_at')->nullable();
            $table->dateTime('fixed_at')->nullable();
            $table->dateTime('postponed_at')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->dateTime('reported_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('resumed_at')->nullable();
            $table->dateTime('suspended_at')->nullable();

            // Permissions

            $table->bigInteger('gids')->default(0)->unsigned();
            $table->bigInteger('po')->default(0)->unsigned();
            $table->bigInteger('pg')->default(0)->unsigned();
            $table->bigInteger('pw')->default(0)->unsigned();

            // Status

            $table->bigInteger('status')->default(0)->unsigned();
            $table->bigInteger('rank')->default(0);
            $table->bigInteger('size')->default(0);

            // Matrix

            $table->json('matrix')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->bigInteger('x')->nullable();
            $table->bigInteger('y')->nullable();
            $table->bigInteger('z')->nullable();
            $table->decimal('r', 65, 10)->nullable()->default(null);
            $table->decimal('theta', 10, 6)->nullable()->default(null);
            $table->decimal('rho', 10, 6)->nullable()->default(null);
            $table->decimal('phi', 10, 6)->nullable()->default(null);
            $table->decimal('elevation', 65, 10)->nullable()->default(null);
            $table->decimal('latitude', 8, 6)->nullable()->default(null);
            $table->decimal('longitude', 9, 6)->nullable()->default(null);

            // Flags

            $table->boolean('active')->default(1)->index();
            $table->boolean('canceled')->default(0);
            $table->boolean('closed')->default(0);
            $table->boolean('completed')->default(0);
            $table->boolean('duplicate')->default(0);
            $table->boolean('featured')->default(0);
            $table->boolean('fixed')->default(0);
            $table->boolean('flagged')->default(0);
            $table->boolean('internal')->default(0);
            $table->boolean('locked')->default(0);
            $table->boolean('pending')->default(0);
            $table->boolean('planned')->default(0);
            $table->boolean('prioritized')->default(0);
            $table->boolean('problem')->default(0);
            $table->boolean('published')->default(0);
            $table->boolean('released')->default(0);
            $table->boolean('retired')->default(0);
            $table->boolean('resolved')->default(0);
            $table->boolean('special')->default(0);
            $table->boolean('suspended')->default(0);
            $table->boolean('unknown')->default(0);

            // Strings

            $table->string('label')->default('');
            $table->string('title')->default('');
            $table->string('byline')->default('');
            $table->string('slug')->nullable()->default(null)->index();
            $table->string('url')->default('');
            $table->string('description')->default('');
            $table->string('introduction')->default('');
            $table->mediumText('content')->nullable();
            $table->mediumText('summary')->nullable();
            $table->string('locale')->default('');

            // Finances

            $table->string('currency')->default('');
            $table->decimal('amount', 19, 4)->nullable()->default(null);
            $table->decimal('bonus', 19, 4)->nullable()->default(null);
            $table->decimal('bonus_rate', 8, 4)->nullable()->default(null);
            $table->decimal('commission', 19, 4)->nullable()->default(null);
            $table->decimal('commission_rate', 8, 4)->nullable()->default(null);
            $table->decimal('estimate', 19, 4)->nullable()->default(null);
            $table->decimal('fees', 19, 4)->nullable()->default(null);
            $table->decimal('materials', 19, 4)->nullable()->default(null);
            $table->decimal('services', 19, 4)->nullable()->default(null);
            $table->decimal('shipping', 19, 4)->nullable()->default(null);
            $table->decimal('subtotal', 19, 4)->nullable()->default(null);
            $table->decimal('taxable', 19, 4)->nullable()->default(null);
            $table->decimal('tax_rate', 8, 4)->nullable()->default(null);
            $table->decimal('taxes', 19, 4)->nullable()->default(null);
            $table->decimal('total', 19, 4)->nullable()->default(null);

            // UI

            $table->string('icon')->default('');
            $table->string('image')->default('');
            $table->string('avatar')->default('');
            $table->json('ui')->nullable()->default(new Expression('(JSON_OBJECT())'));

            // JSON

            $table->json('assets')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->json('meta')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->json('notes')->nullable()->default(new Expression('(JSON_ARRAY())'))->comment('Array of note objects');
            $table->json('params')->default(new Expression('(JSON_OBJECT())'));
            $table->json('options')->nullable()->default(new Expression('(JSON_OBJECT())'));
            $table->json('sources')->nullable()->default(new Expression('(JSON_OBJECT())'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_regions');
    }
};
