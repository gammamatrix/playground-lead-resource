<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Requests\Teammate;

use Playground\Http\Requests\StoreRequest as BaseStoreRequest;

/**
 * \Playground\Lead\Resource\Http\Requests\Teammate\StoreRequest
 */
class StoreRequest extends BaseStoreRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [
        'teammate_type' => ['nullable', 'string'],
        'owned_by_id' => ['nullable', 'uuid'],
        'parent_id' => ['nullable', 'uuid'],
        'campaign_id' => ['nullable', 'uuid'],
        'goal_id' => ['nullable', 'uuid'],
        'lead_id' => ['nullable', 'uuid'],
        'opportunity_id' => ['nullable', 'uuid'],
        'plan_id' => ['nullable', 'uuid'],
        'region_id' => ['nullable', 'uuid'],
        'report_id' => ['nullable', 'uuid'],
        'source_id' => ['nullable', 'uuid'],
        'task_id' => ['nullable', 'uuid'],
        'team_id' => ['nullable', 'uuid'],
        'matrix_id' => ['nullable', 'uuid'],
        'canceled_at' => ['nullable', 'string'],
        'closed_at' => ['nullable', 'string'],
        'embargo_at' => ['nullable', 'string'],
        'fixed_at' => ['nullable', 'string'],
        'planned_end_at' => ['nullable', 'string'],
        'planned_start_at' => ['nullable', 'string'],
        'postponed_at' => ['nullable', 'string'],
        'published_at' => ['nullable', 'string'],
        'released_at' => ['nullable', 'string'],
        'resumed_at' => ['nullable', 'string'],
        'resolved_at' => ['nullable', 'string'],
        'suspended_at' => ['nullable', 'string'],
        'timer_end_at' => ['nullable', 'string'],
        'timer_start_at' => ['nullable', 'string'],
        'gids' => ['integer'],
        'po' => ['integer'],
        'pg' => ['integer'],
        'pw' => ['integer'],
        'only_admin' => ['boolean'],
        'only_user' => ['boolean'],
        'only_guest' => ['boolean'],
        'allow_public' => ['boolean'],
        'status' => ['integer'],
        'rank' => ['integer'],
        'size' => ['integer'],
        'matrix' => ['nullable', 'array'],
        'x' => ['nullable', 'integer'],
        'y' => ['nullable', 'integer'],
        'z' => ['nullable', 'integer'],
        'r' => ['nullable', 'numeric'],
        'theta' => ['nullable', 'numeric'],
        'rho' => ['nullable', 'numeric'],
        'phi' => ['nullable', 'numeric'],
        'elevation' => ['nullable', 'numeric'],
        'latitude' => ['nullable', 'numeric'],
        'longitude' => ['nullable', 'numeric'],
        'active' => ['boolean'],
        'canceled' => ['boolean'],
        'closed' => ['boolean'],
        'completed' => ['boolean'],
        'cron' => ['boolean'],
        'duplicate' => ['boolean'],
        'featured' => ['boolean'],
        'fixed' => ['boolean'],
        'flagged' => ['boolean'],
        'internal' => ['boolean'],
        'locked' => ['boolean'],
        'pending' => ['boolean'],
        'planned' => ['boolean'],
        'prioritized' => ['boolean'],
        'problem' => ['boolean'],
        'published' => ['boolean'],
        'released' => ['boolean'],
        'resolved' => ['boolean'],
        'retired' => ['boolean'],
        'sms' => ['boolean'],
        'special' => ['boolean'],
        'suspended' => ['boolean'],
        'unknown' => ['boolean'],
        'locale' => ['string'],
        'label' => ['string'],
        'title' => ['string', 'required'],
        'byline' => ['string'],
        'slug' => ['nullable', 'string'],
        'url' => ['string'],
        'description' => ['string'],
        'introduction' => ['string'],
        'content' => ['nullable', 'string'],
        'summary' => ['nullable', 'string'],
        'email' => ['nullable', 'string'],
        'phone' => ['nullable', 'string'],
        'team_role' => ['nullable', 'string'],
        'currency' => ['nullable', 'string'],
        'amount' => ['nullable', 'decimal'],
        'bonus' => ['nullable', 'decimal'],
        'bonus_rate' => ['nullable', 'decimal'],
        'commission' => ['nullable', 'decimal'],
        'commission_rate' => ['nullable', 'decimal'],
        'estimate' => ['nullable', 'decimal'],
        'fees' => ['nullable', 'decimal'],
        'materials' => ['nullable', 'decimal'],
        'services' => ['nullable', 'decimal'],
        'shipping' => ['nullable', 'decimal'],
        'subtotal' => ['nullable', 'decimal'],
        'taxable' => ['nullable', 'decimal'],
        'tax_rate' => ['nullable', 'decimal'],
        'taxes' => ['nullable', 'decimal'],
        'total' => ['nullable', 'decimal'],
        'icon' => ['string'],
        'image' => ['string'],
        'avatar' => ['string'],
        'ui' => ['nullable', 'array'],
        'address' => ['nullable', 'array'],
        'assets' => ['nullable', 'array'],
        'contact' => ['nullable', 'array'],
        'meta' => ['nullable', 'array'],
        'options' => ['nullable', 'array'],
        'sources' => ['nullable', 'array'],
        '_return_url' => ['nullable', 'url'],
    ];

    protected string $slug_table = 'lead_teammates';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        /**
         * @var array<string, bool> $revisions
         */
        $revisions = config('playground-lead-resource.revisions');

        if (! empty($revisions['optional'])) {
            $rules['revision'] = 'bool';
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $input = [];

        $this->filterContentFields($input);
        $this->filterCommonFields($input);
        $this->filterStatus($input);
        $this->filterSystemFields($input);

        if (! empty($input)) {
            $this->merge($input);
        }
    }
}
