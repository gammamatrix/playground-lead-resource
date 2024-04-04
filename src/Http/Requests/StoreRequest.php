<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Http\Requests;

use Playground\Http\Requests\StoreRequest as FormRequest;

/**
 * \Playground\Lead\Resource\Http\Requests\StoreRequest
 */
class StoreRequest extends FormRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [
        'owned_by_id' => ['nullable', 'uuid'],
        'parent_id' => ['nullable', 'uuid'],
        'model_type' => ['nullable', 'string'],
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
        'teammate_id' => ['nullable', 'uuid'],
        'start_at' => ['nullable', 'string'],
        'planned_start_at' => ['nullable', 'string'],
        'end_at' => ['nullable', 'string'],
        'planned_end_at' => ['nullable', 'string'],
        'calculated_at' => ['nullable', 'string'],
        'canceled_at' => ['nullable', 'string'],
        'closed_at' => ['nullable', 'string'],
        'embargo_at' => ['nullable', 'string'],
        'fixed_at' => ['nullable', 'string'],
        'postponed_at' => ['nullable', 'string'],
        'published_at' => ['nullable', 'string'],
        'released_at' => ['nullable', 'string'],
        'reported_at' => ['nullable', 'string'],
        'resolved_at' => ['nullable', 'string'],
        'resumed_at' => ['nullable', 'string'],
        'suspended_at' => ['nullable', 'string'],
        'gids' => ['integer'],
        'po' => ['integer'],
        'pg' => ['integer'],
        'pw' => ['integer'],
        'status' => ['integer'],
        'rank' => ['integer'],
        'size' => ['integer'],
        'matrix' => ['nullable', 'array'],
        'x' => ['nullable', 'integer'],
        'y' => ['nullable', 'integer'],
        'z' => ['nullable', 'integer'],
        'r' => ['nullable', 'float'],
        'theta' => ['nullable', 'float'],
        'rho' => ['nullable', 'float'],
        'phi' => ['nullable', 'float'],
        'elevation' => ['nullable', 'float'],
        'latitude' => ['nullable', 'float'],
        'longitude' => ['nullable', 'float'],
        'active' => ['boolean'],
        'canceled' => ['boolean'],
        'closed' => ['boolean'],
        'completed' => ['boolean'],
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
        'retired' => ['boolean'],
        'resolved' => ['boolean'],
        'suspended' => ['boolean'],
        'unknown' => ['boolean'],
        'label' => ['string'],
        'title' => ['string', 'required'],
        'byline' => ['string'],
        'slug' => ['nullable', 'string'],
        'url' => ['string'],
        'description' => ['string'],
        'introduction' => ['string'],
        'content' => ['nullable', 'string'],
        'summary' => ['nullable', 'string'],
        'locale' => ['string'],
        'currency' => ['string'],
        'amount' => ['nullable', 'float'],
        'bonus' => ['nullable', 'float'],
        'bonus_rate' => ['nullable', 'float'],
        'commission' => ['nullable', 'float'],
        'commission_rate' => ['nullable', 'float'],
        'estimate' => ['nullable', 'float'],
        'fees' => ['nullable', 'float'],
        'materials' => ['nullable', 'float'],
        'services' => ['nullable', 'float'],
        'shipping' => ['nullable', 'float'],
        'subtotal' => ['nullable', 'float'],
        'taxable' => ['nullable', 'float'],
        'tax_rate' => ['nullable', 'float'],
        'taxes' => ['nullable', 'float'],
        'total' => ['nullable', 'float'],
        'icon' => ['string'],
        'image' => ['string'],
        'avatar' => ['string'],
        'ui' => ['nullable', 'array'],
        'assets' => ['nullable', 'array'],
        'meta' => ['nullable', 'array'],
        'options' => ['nullable', 'array'],
        'sources' => ['nullable', 'array'],
        '_return_url' => ['nullable', 'url'],
    ];

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

        if ($this->exists('currency')) {
            $input['currency'] = $this->filterHtml($this->input('currency'));
        }

        if ($this->exists('amount')) {
            $input['amount'] = $this->filterFloat($this->input('amount'));
        }

        if ($this->exists('bonus')) {
            $input['bonus'] = $this->filterFloat($this->input('bonus'));
        }

        if ($this->exists('bonus_rate')) {
            $input['bonus_rate'] = $this->filterPercent($this->input('bonus_rate'));
        }

        if ($this->exists('commission')) {
            $input['commission'] = $this->filterFloat($this->input('commission'));
        }

        if ($this->exists('commission_rate')) {
            $input['commission_rate'] = $this->filterPercent($this->input('commission_rate'));
        }

        if ($this->exists('estimate')) {
            $input['estimate'] = $this->filterFloat($this->input('estimate'));
        }

        if ($this->exists('fees')) {
            $input['fees'] = $this->filterFloat($this->input('fees'));
        }

        if ($this->exists('materials')) {
            $input['materials'] = $this->filterFloat($this->input('materials'));
        }

        if ($this->exists('services')) {
            $input['services'] = $this->filterFloat($this->input('services'));
        }

        if ($this->exists('shipping')) {
            $input['shipping'] = $this->filterFloat($this->input('shipping'));
        }

        if ($this->exists('subtotal')) {
            $input['subtotal'] = $this->filterFloat($this->input('subtotal'));
        }

        if ($this->exists('taxable')) {
            $input['taxable'] = $this->filterFloat($this->input('taxable'));
        }

        if ($this->exists('tax_rate')) {
            $input['tax_rate'] = $this->filterPercent($this->input('tax_rate'));
        }

        if ($this->exists('taxes')) {
            $input['taxes'] = $this->filterFloat($this->input('taxes'));
        }
        if (! empty($input)) {
            $this->merge($input);
        }
    }
}
