<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class InvoicesFilter extends ApiFilter
{
    protected array $safeParms = [
        'customerId' => ['eq'],
        'amount' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'status' => ['eq', 'ne'],
        'billedDate' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'paidDate' => ['eq', 'lt', 'lte', 'gt', 'gte'],
    ];

    protected array $columnMap = [
        'customerId' => 'costumer_id',
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date',
    ];

    protected array $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    public function transform(Request $request): array
    {
        $eloQuery = [];
        foreach ($this->safeParms as $parm => $operators) {
            $query = $request->query($parm);
            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$parm] ?? $parm;
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloQuery;
    }
}
