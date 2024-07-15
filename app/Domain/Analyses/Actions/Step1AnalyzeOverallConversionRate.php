<?php

namespace DDD\Domain\Analyses\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use DivisionByZeroError;
use DDD\Domain\Analyses\Analysis;

class Step1AnalyzeOverallConversionRate
{
    use AsAction;

    function handle(Analysis $analysis, $subjectFunnel, $comparisonFunnels)
    {
        /**
         * Get the conversion rate for the subject funnel
         */
        $subjectFunnelConversionRate = $subjectFunnel['report']['overallConversionRate'];

        /**
         * Get the conversion rates for the comparison funnels
         */
        $comparisonFunnelsConversionRates = [];

        foreach ($comparisonFunnels as $key => $comparisonFunnel) {
            array_push($comparisonFunnelsConversionRates, $comparisonFunnel['report']['overallConversionRate']);
        }

        /**
         * Get the median of the comparison conversion rates
         */
        $medianOfComparisonConversionRates = $this->calculateMedian($comparisonFunnelsConversionRates);

        /**
         * Get subject funnel conversion rate percentage higher/lower
         */
        try {
            $percentageDifference = ($subjectFunnelConversionRate - $medianOfComparisonConversionRates) / $medianOfComparisonConversionRates * 100;
        } catch(DivisionByZeroError $e){
            $percentageDifference = 0;
        }

        /**
         * Format the percentage difference to include a + or - sign
         */
        // $formattedPercentageDifference = ($percentageDifference >= 0 ? '+' : '') . number_format($percentageDifference, 2) . ($percentageDifference >= 0 ? '% higher' : '% lower');
        $formattedPercentageDifference = number_format($percentageDifference, 2);

        // Update dashboard
        $analysis->dashboard->update([
            'subject_funnel_performance' => $formattedPercentageDifference,
        ]);

        // Update analysis
        $analysis->update([
            'subject_funnel_performance' => $formattedPercentageDifference,
            // 'content' => '
            //     <p>' . $formattedPercentageDifference . ($formattedPercentageDifference <= 0 ? '% lower' : '% higher') . ' than comparisons</p>
            // ',
        ]);

        return $analysis;
    }

    function calculateMedian($arrayOfNumbers) {
        sort($arrayOfNumbers); // Step 1: Sort the array
        $count = count($arrayOfNumbers);
        
        if ($count % 2 == 0) {
            // If the number of elements is even
            $middle1 = $arrayOfNumbers[$count / 2 - 1];
            $middle2 = $arrayOfNumbers[$count / 2];
            $median = ($middle1 + $middle2) / 2;
        } else {
            // If the number of elements is odd
            $median = $arrayOfNumbers[floor($count / 2)];
        }
        
        return $median;
    }
}
