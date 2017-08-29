<?php

class clientLogin
{
    public $username; // string
    public $password; // string
    public $source; // string
}

class clientLoginResponse
{
    public $clientLoginResult; // long
}

class partnerLogin
{
    public $appToken; // long
    public $username; // string
    public $password; // string
    public $source; // string
}

class partnerLoginResponse
{
    public $partnerLoginResult; // long
}

class getAccount
{
}

class getAccountResponse
{
    public $getAccountResult; // AdvertiserAccount
}

class AdvertiserAccount
{
    public $advertiserName; // string
    public $email; // string
    public $currency; // string
    public $timezone; // string
    public $country; // string
}

class apiHeader
{
    public $authToken; // long
    public $appToken; // long
    public $clientVersion; // string
}

class getCampaigns
{
    public $campaignSelector; // CampaignSelectors
}

class CampaignSelectors
{
    public $campaignIDs; // ArrayOfInt
    public $budgetIDs; // ArrayOfInt
    public $campaignStatus; // ArrayOfCampaignStatus
    public $biddingStrategy; // ArrayOfBiddingStrategy
}

class CampaignStatus
{
    const RUNNING = 'RUNNING';
    const DEAD = 'DEAD';
    const NOT_RUNNING = 'NOT_RUNNING';
}

class biddingStrategy
{
    const Cpm = 'Cpm';
    const Cpc = 'Cpc';
    const Cpa = 'Cpa';
    const Cpo = 'Cpo';
}

class getCampaignsResponse
{
    public $getCampaignsResult; // ArrayOfCampaign
}

class campaign
{
    public $campaignID; // int
    public $campaignName; // string
    public $endDate; // string
    public $campaignBid; // bidInformation
    public $budgetID; // int
    public $remainingDays; // int
    public $status; // CampaignStatus
    public $categoryBids; // ArrayOfCategoryBid
}

class bidInformation
{
    public $biddingStrategy; // biddingStrategy
    public $cpcBid; // CPCBid
    public $cpaBid; // CPABid
}

class CPCBid
{
    public $cpc; // double
}

class CPABid
{
    public $postClick; // int
    public $postView; // int
    public $commission; // double
    public $percent; // double
}

class categoryBid
{
    public $campaignCategoryUID; // int
    public $campaignID; // int
    public $categoryID; // int
    public $selected; // boolean
    public $bidInformation; // bidInformation
}

class mutateCampaigns
{
    public $listOfCampaignMutates; // ArrayOfCampaignMutate
}

class campaignMutate
{
    public $campaign; // campaign
}

class mutate
{
    public $operationMutate; // OperationMutate
}

class OperationMutate
{
    const ADD = 'ADD';
    const RESET = 'RESET';
    const SET = 'SET';
}

class mutateCampaignsResponse
{
    public $listOfJobResponse; // ArrayOfCampaignMutateJobResponse
}

class campaignMutateJobResponse
{
    public $campaignMutate; // campaignMutate
}

class JobResponse
{
    public $jobID; // long
    public $jobStatus; // JobStatus
    public $apiException; // ApiExceptionData
}

class JobStatus
{
    const Pending = 'Pending';
    const InProgress = 'InProgress';
    const Completed = 'Completed';
    const Failed = 'Failed';
}

class ApiExceptionData
{
    public $errorCode; // ApiExceptionErrorCode
    public $errors; // ArrayOfApiError
    public $message; // string
    public $trigger; // string
}

class ApiExceptionErrorCode
{
    const NoError = 'NoError';
    const BadAuthentification = 'BadAuthentification';
    const AuthTokenExpired = 'AuthTokenExpired';
    const InvalidAppTokenAuthToken = 'InvalidAppTokenAuthToken';
    const AuthentificationNotExist = 'AuthentificationNotExist';
    const UserAccessDisabled = 'UserAccessDisabled';
    const UserAccessNotAllowed = 'UserAccessNotAllowed';
    const MissingData = 'MissingData';
    const OperationNotSupported = 'OperationNotSupported';
    const InvalidID = 'InvalidID';
    const AccessToThisJobNotAllowed = 'AccessToThisJobNotAllowed';
    const InvalidFieldValue = 'InvalidFieldValue';
    const UnexpectedError = 'UnexpectedError';
    const AppTokenNotSupportedForPartnerLogin = 'AppTokenNotSupportedForPartnerLogin';
}

class apiError
{
    public $code; // ApiErrorCode
    public $detail; // string
    public $field; // string
    public $index; // int
    public $isExemptable; // boolean
    public $textIndex; // int
    public $textLength; // int
    public $trigger; // string
}

class ApiErrorCode
{
    const NoError = 'NoError';
    const MissingMutateStructure = 'MissingMutateStructure';
    const MissingMutateObject = 'MissingMutateObject';
    const MissingJobReportStructure = 'MissingJobReportStructure';
    const UnexpectedError = 'UnexpectedError';
}

class getCategories
{
    public $categorySelector; // CategorySelectors
}

class CategorySelectors
{
    public $categoryIDs; // ArrayOfInt
    public $selected; // boolean
}

class getCategoriesResponse
{
    public $getCategoriesResult; // ArrayOfCategory
}

class category
{
    public $avgPrice; // double
    public $categoryID; // int
    public $categoryName; // string
    public $numberOfProducts; // int
    public $selected; // boolean
}

class mutateCategories
{
    public $listofCategoryMutates; // ArrayOfCategoryMutate
}

class categoryMutate
{
    public $category; // category
}

class mutateCategoriesResponse
{
    public $listOfJobResponse; // ArrayOfCategoryMutateJobResponse
}

class categoryMutateJobResponse
{
    public $categoryMutate; // categoryMutate
}

class getBudgets
{
    public $budgetSelector; // BudgetSelectors
}

class BudgetSelectors
{
    public $budgetIDs; // ArrayOfInt
}

class getBudgetsResponse
{
    public $getBudgetsResult; // ArrayOfBudget
}

class budget
{
    public $budgetID; // int
    public $budgetName; // string
    public $totalAmount; // int
    public $remainingBudget; // double
    public $remainingBudgetUpdated; // string
}

class scheduleReportJob
{
    public $reportJob; // ReportJob
}

class ReportJob
{
    public $reportSelector; // ReportSelector
    public $reportType; // ReportType
    public $aggregationType; // AggregationType
    public $startDate; // string
    public $endDate; // string
    public $selectedColumns; // ArrayOfReportColumn
    public $isResultGzipped; // boolean
}

class ReportSelector
{
    public $CategoryIDs; // ArrayOfInt
    public $CampaignIDs; // ArrayOfInt
    public $BannerIDs; // ArrayOfInt
}

class ReportType
{
    const Campaign = 'Campaign';
    const Banner = 'Banner';
    const Category = 'Category';
}

class AggregationType
{
    const Hourly = 'Hourly';
    const Daily = 'Daily';
}

class ReportColumn
{
    const clicks = 'clicks';
    const impressions = 'impressions';
    const ctr = 'ctr';
    const revcpc = 'revcpc';
    const ecpm = 'ecpm';
    const cost = 'cost';
    const sales = 'sales';
    const convRate = 'convRate';
    const orderValue = 'orderValue';
    const salesPostView = 'salesPostView';
    const convRatePostView = 'convRatePostView';
    const orderValuePostView = 'orderValuePostView';
    const costOfSale = 'costOfSale';
    const impressionWin = 'impressionWin';
    const costPerOrder = 'costPerOrder';
}

class scheduleReportJobResponse
{
    public $jobResponse; // ReportJobResponse
}

class ReportJobResponse
{
    public $reportJob; // ReportJob
}

class getJobStatus
{
    public $jobID; // long
}

class getJobStatusResponse
{
    public $getJobStatusResult; // JobStatus
}

class getReportDownloadUrl
{
    public $jobID; // long
}

class getReportDownloadUrlResponse
{
    public $jobURL; // string
}

class getStatisticsLastUpdate
{
}

class getStatisticsLastUpdateResponse
{
    public $getStatisticsLastUpdateResult; // ArrayOfStatUpdate
}

class StatUpdate
{
    public $Type; // StatType
    public $Date; // dateTime
}

class StatType
{
    const ClicksAndImpressions = 'ClicksAndImpressions';
    const Sales = 'Sales';
}


/**
 * CriteoAdvertiserAPI class
 *
 *
 *
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class CriteoAdvertiserAPI extends SoapClient
{

    private static $classmap = array(
        'clientLogin' => 'clientLogin',
        'clientLoginResponse' => 'clientLoginResponse',
        'partnerLogin' => 'partnerLogin',
        'partnerLoginResponse' => 'partnerLoginResponse',
        'getAccount' => 'getAccount',
        'getAccountResponse' => 'getAccountResponse',
        'AdvertiserAccount' => 'AdvertiserAccount',
        'apiHeader' => 'apiHeader',
        'getCampaigns' => 'getCampaigns',
        'CampaignSelectors' => 'CampaignSelectors',
        'CampaignStatus' => 'CampaignStatus',
        'biddingStrategy' => 'biddingStrategy',
        'getCampaignsResponse' => 'getCampaignsResponse',
        'campaign' => 'campaign',
        'bidInformation' => 'bidInformation',
        'CPCBid' => 'CPCBid',
        'CPABid' => 'CPABid',
        'categoryBid' => 'categoryBid',
        'mutateCampaigns' => 'mutateCampaigns',
        'campaignMutate' => 'campaignMutate',
        'mutate' => 'mutate',
        'OperationMutate' => 'OperationMutate',
        'mutateCampaignsResponse' => 'mutateCampaignsResponse',
        'campaignMutateJobResponse' => 'campaignMutateJobResponse',
        'JobResponse' => 'JobResponse',
        'JobStatus' => 'JobStatus',
        'ApiExceptionData' => 'ApiExceptionData',
        'ApiExceptionErrorCode' => 'ApiExceptionErrorCode',
        'apiError' => 'apiError',
        'ApiErrorCode' => 'ApiErrorCode',
        'getCategories' => 'getCategories',
        'CategorySelectors' => 'CategorySelectors',
        'getCategoriesResponse' => 'getCategoriesResponse',
        'category' => 'category',
        'mutateCategories' => 'mutateCategories',
        'categoryMutate' => 'categoryMutate',
        'mutateCategoriesResponse' => 'mutateCategoriesResponse',
        'categoryMutateJobResponse' => 'categoryMutateJobResponse',
        'getBudgets' => 'getBudgets',
        'BudgetSelectors' => 'BudgetSelectors',
        'getBudgetsResponse' => 'getBudgetsResponse',
        'budget' => 'budget',
        'scheduleReportJob' => 'scheduleReportJob',
        'ReportJob' => 'ReportJob',
        'ReportSelector' => 'ReportSelector',
        'ReportType' => 'ReportType',
        'AggregationType' => 'AggregationType',
        'ReportColumn' => 'ReportColumn',
        'scheduleReportJobResponse' => 'scheduleReportJobResponse',
        'ReportJobResponse' => 'ReportJobResponse',
        'getJobStatus' => 'getJobStatus',
        'getJobStatusResponse' => 'getJobStatusResponse',
        'getReportDownloadUrl' => 'getReportDownloadUrl',
        'getReportDownloadUrlResponse' => 'getReportDownloadUrlResponse',
        'getStatisticsLastUpdate' => 'getStatisticsLastUpdate',
        'getStatisticsLastUpdateResponse' => 'getStatisticsLastUpdateResponse',
        'StatUpdate' => 'StatUpdate',
        'StatType' => 'StatType',
    );

    public function CriteoAdvertiserAPI($wsdl = "https://advertising.criteo.com/api/v201010/advertiserservice.asmx?wsdl", $options = array())
    {
        foreach (self::$classmap as $key => $value) {
            if (!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }
        parent::__construct($wsdl, $options);
    }

    /**
     * Client login is secured by using an application token, along with username and password.
     *
     *
     * @param clientLogin $parameters
     * @return clientLoginResponse
     */
    public function clientLogin(clientLogin $parameters)
    {
//        return $this->__soapCall('clientLogin', array($parameters), array(
//                'uri' => 'https://advertising.criteo.com/API/v201010',
//                'soapaction' => ''
//            )
//        );
        
    }

    /**
     * Partner login is secured by using an application token, along with username and password.
     *
     *
     * @param partnerLogin $parameters
     * @return partnerLoginResponse
     */
    public function partnerLogin(partnerLogin $parameters)
    {
        return $this->__soapCall('partnerLogin', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Returns advertiser account information
     *
     * @param getAccount $parameters
     * @return getAccountResponse
     */
    public function getAccount(getAccount $parameters)
    {
        return $this->__soapCall('getAccount', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Returns campaign information, including all CPC bids per category for each campaign.
     *
     * @param getCampaigns $parameters
     * @return getCampaignsResponse
     */
    public function getCampaigns(getCampaigns $parameters)
    {
        return $this->__soapCall('getCampaigns', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Mutate campaign information. Global CPC per campaign and specific Category Bids can be
     * set.
     *
     * @param mutateCampaigns $parameters
     * @return mutateCampaignsResponse
     */
    public function mutateCampaigns(mutateCampaigns $parameters)
    {
        return $this->__soapCall('mutateCampaigns', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Returns category information. All categories linked to that account are returned.
     *
     * @param getCategories $parameters
     * @return getCategoriesResponse
     */
    public function getCategories(getCategories $parameters)
    {
        return $this->__soapCall('getCategories', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Mutate category information. Can only be used to define which categories will be selected
     * for Bidding and Reporting.
     *
     * @param mutateCategories $parameters
     * @return mutateCategoriesResponse
     */
    public function mutateCategories(mutateCategories $parameters)
    {
        return $this->__soapCall('mutateCategories', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Returns buget information.
     *
     * @param getBudgets $parameters
     * @return getBudgetsResponse
     */
    public function getBudgets(getBudgets $parameters)
    {
        return $this->__soapCall('getBudgets', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Schedules a statistic report Job.
     *
     * @param scheduleReportJob $parameters
     * @return scheduleReportJobResponse
     */
    public function scheduleReportJob(scheduleReportJob $parameters)
    {
        return $this->__soapCall('scheduleReportJob', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Returns status of a asynchronous job (report or mutate). Currently applies to jobs created
     * for mutateCampaign and scheduleReportJob.
     *
     * @param getJobStatus $parameters
     * @return getJobStatusResponse
     */
    public function getJobStatus(getJobStatus $parameters)
    {
        return $this->__soapCall('getJobStatus', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Retrieve reportURL.
     *
     * @param getReportDownloadUrl $parameters
     * @return getReportDownloadUrlResponse
     */
    public function getReportDownloadUrl(getReportDownloadUrl $parameters)
    {
        return $this->__soapCall('getReportDownloadUrl', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

    /**
     * Get statistics last update.
     *
     * @param getStatisticsLastUpdate $parameters
     * @return getStatisticsLastUpdateResponse
     */
    public function getStatisticsLastUpdate(getStatisticsLastUpdate $parameters)
    {
        return $this->__soapCall('getStatisticsLastUpdate', array($parameters), array(
                'uri' => 'https://advertising.criteo.com/API/v201010',
                'soapaction' => ''
            )
        );
    }

}

?>
