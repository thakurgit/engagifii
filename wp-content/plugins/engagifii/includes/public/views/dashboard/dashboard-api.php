<?php
$obj      = new Engagifii_API();
$site_url = site_url();
$options  = get_option('ebt_api_settings');
$user_id  = get_current_user_id();
$user     = get_userdata($user_id);
$userEmail = $user->user_email;
$args     = array(
    'post_type' => 'get',
    'author'    => $user_id,
);
$tenant_code = 'psba';
$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE2MDk5MDcxMTEsImV4cCI6MTYwOTkxNDMxMSwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxIiwiYXV0aF90aW1lIjoxNjA5OTA3MTExLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiJodHRwczovL2VuZ2FnaWZpaWlkc3RvcmFnZS5ibG9iLmNvcmUud2luZG93cy5uZXQ6NDQzL3Byb2ZpbGVwaWNzL3Byb2ZpbGUtcGljODgwMzBjYzktYWMxNS00NzlkLWJahY2ItNmYzMTAzNDBkNmMxLnBuZyIsInBpY3R1cmUtaWNvbiI6Imh0dHBzOi8vZW5nYWdpZmlpaWRzdG9yYWdlLmJsb2IuY29yZS53aW5kb3dzLm5ldDo0NDMvcHJvZmlsZXBpY3MtaWNvbi9wcm9maWxlLXBpYzg4MDMwY2M5LWFjMTUtNDc5ZC1iYWNiLTZmMzEwMzQwZDZjMS5wbmciLCJnaXZlbl9uYW1lIjoiRW5nYWdpZmlpIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjEvNi8yMDIxIDQ6MjM6MjIgQU0iLCJjdXJyZW50LWxvZ2luIjoiMS82LzIwMjEgNDoyNToxMSBBTSIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.siQUIA6URga2cwvFDOXdRs1Y2l71KH65hijXt_X-wEgN6o5to-TowYneiYPfdq9zBUilpnoJPsx73m7JUwer7YPMdHOBZCEcNYcOUPpjcTEfut_Bflj_CYfQb-RcUIbsdzoWEDJB-hRg-g-V-1CEWOsFbnRWxbPOliZnnco-YW0GGFZErrXhwb4YixwtjBidyaffomtn1TXN8pjwq2kq3SrpyzCPTs8H5WqXj7sA3AmA9fFWBFZQsbgCxbg_bmeYGE4S9YWt2NUZjT39ld1WrxAVuzx5F1VX0iVYMe0YIMBNB075upMvue1Tj3K7k-1j0oQnl_3anZ2Ph5ysUbEXQQ';

$authenticationAward = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDU5MjEwNDAsImV4cCI6MTcwNTkyODI0MCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA1OTIxMDQwLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLWljb24iOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjAxLzIyLzIwMjQgMTA6NTQ6NDciLCJjdXJyZW50LWxvZ2luIjoiMDEvMjIvMjAyNCAxMDo1NzoyMCIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.g-LxFKPj00MXMUkupYlN1trw3i_mL-0u3kGlQ31qli5VzQI-NFlnX5skewt-9OJKPOfItfMbM8Rdt5_qf4noFk3WEExwGjSHbY1C8RJRqCFbf9WybM2kqWk9A3YFy9ZbZD5XUd4lojb3cZQdSpMdcBvlMhTTcf33xhgsrf4Uy2BT1W8SP4ukZb6AqvkSeCtCrLEaUoWwn6kGcFjiOxycbdn1x9Gzy5C0K6eUoWi1Q2oeobDhgKXiRVSXuFn7c62N0U37t-1AnD2xXDPaJjm-mpWmx1OX1ZZqhb73Tolt_S5dwIzigI7xhSzviecp90mrE3Z6IkHQmt11nkjSqnLHEQ';

$profileId = '5e7f3fed-c3f8-4b38-a25f-4f6a32511337';
$url = "https://engagifii-preview4-crm.azurewebsites.net/api/v1/GetPersonDetailByEmail/".$userEmail."/".$tenant_code;
$awardURL = 'https://engagifii-qa-tna.azurewebsites.net/api/v1/Awards/AwardsCertificationsByPeople/'.$profileId;
$awardpayload = '{"itemCount":10,"pageNumber":1,"sortBy":"name","sortDirection":"asc","filterBody":{}}';

// Initialize cURL multi handler
$multiCurl = curl_multi_init();

// Initialize individual cURL handles
$ch1 = createCurl($url, array("cache-control: no-cache", "content-type: application/json", "tenant-code:".$tenant_code, $authentication));
$ch2 = createCurl($awardURL, array("cache-control: no-cache", "content-type: application/json", "tenant-code:accg", $authenticationAward), true, $awardpayload);

// Add the cURL handles to the multi handler
curl_multi_add_handle($multiCurl, $ch1);
curl_multi_add_handle($multiCurl, $ch2);

// Execute all cURL requests simultaneously
do {
    curl_multi_exec($multiCurl, $running);
} while ($running > 0);

// Close the cURL handles
curl_multi_remove_handle($multiCurl, $ch1);
curl_multi_remove_handle($multiCurl, $ch2);
curl_multi_close($multiCurl);

// Get the responses
$response1 = curl_multi_getcontent($ch1);
$response2 = curl_multi_getcontent($ch2);

// Decode the responses
$peopleDATA = json_decode($response1);
$awardData = json_decode($response2);

// Close the cURL session
curl_close($ch1);
curl_close($ch2);

// print_r($peopleDATA);
// print_r($awardData);

function createCurl($url, $headers, $isPost = false, $postData = "") {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
    ));
    if ($isPost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    return $ch;
}
$current_user_posts = get_posts($args);
$total              = count($current_user_posts);

