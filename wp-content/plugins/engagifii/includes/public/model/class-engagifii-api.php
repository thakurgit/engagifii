<?php
/**
 * Engagifii API
 *
 * Handles Engagifii-API endpoint requests.
 *
 * @package Engagifii/API
 * @since   1.0.0
 */ 

defined( 'ABSPATH' ) || exit;

/**
 * API class.
 */
class Engagifii_API{
	/*
	* send Engagifii API POST request
	*/
	protected function submitApiRequest($requestUrl,$requestData,$requestType="POST", $module)
	{
		$options = get_option( 'ebt_api_settings' );
		$prepareApiResponse = array();
		$authentication = '';
		$ebt_api_url ='';
		if(is_array($options) && !empty($options))
		{
			
			if($module == 'legislation'){
				//print_r(json_encode($requestData));
				$ebt_api_url = $options['lbt_api_url'];
				$ebt_tenant_code = $options['lbt_tenant_code'];
			}else if($module == 'legislation-auth'){
				$ebt_api_url = $options['lbt_api_url'];
				$ebt_tenant_code = $options['lbt_tenant_code'];
				$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE2MDk5MDcxMTEsImV4cCI6MTYwOTkxNDMxMSwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxIiwiYXV0aF90aW1lIjoxNjA5OTA3MTExLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiJodHRwczovL2VuZ2FnaWZpaWlkc3RvcmFnZS5ibG9iLmNvcmUud2luZG93cy5uZXQ6NDQzL3Byb2ZpbGVwaWNzL3Byb2ZpbGUtcGljODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxLnBuZyIsInBpY3R1cmUtc21hbGwiOiJodHRwczovL2VuZ2FnaWZpaWlkc3RvcmFnZS5ibG9iLmNvcmUud2luZG93cy5uZXQ6NDQzL3Byb2ZpbGVwaWNzLXNtL3Byb2ZpbGUtcGljODgwMzBjYzktYWMxNS00NzlkLWJhY2ItNmYzMTAzNDBkNmMxLnBuZyIsInBpY3R1cmUtaWNvbiI6Imh0dHBzOi8vZW5nYWdpZmlpaWRzdG9yYWdlLmJsb2IuY29yZS53aW5kb3dzLm5ldDo0NDMvcHJvZmlsZXBpY3MtaWNvbi9wcm9maWxlLXBpYzg4MDMwY2M5LWFjMTUtNDc5ZC1iYWNiLTZmMzEwMzQwZDZjMS5wbmciLCJnaXZlbl9uYW1lIjoiRW5nYWdpZmlpIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjEvNi8yMDIxIDQ6MjM6MjIgQU0iLCJjdXJyZW50LWxvZ2luIjoiMS82LzIwMjEgNDoyNToxMSBBTSIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.siQUIA6URga2cwvFDOXdRs1Y2l71KH65hijXt_X-wEgN6o5to-TowYneiYPfdq9zBUilpnoJPsx73m7JUwer7YPMdHOBZCEcNYcOUPpjcTEfut_Bflj_CYfQb-RcUIbsdzoWEDJB-hRg-g-V-1CEWOsFbnRWxbPOliZnnco-YW0GGFZErrXhwb4YixwtjBidyaffomtn1TXN8pjwq2kq3SrpyzCPTs8H5WqXj7sA3AmA9fFWBFZQsbgCxbg_bmeYGE4S9YWt2NUZjT39ld1WrxAVuzx5F1VX0iVYMe0YIMBNB075upMvue1Tj3K7k-1j0oQnl_3anZ2Ph5ysUbEXQQ';
			}
			else if($module == 'endorsement' || $module == 'courses' || $module == 'classes'){
				$ebt_api_url = $options['ebt_api_url'];
				$ebt_tenant_code = $options['ebt_tenant_code'];
				if($module == 'courses'){
				$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDQzNzk4MzQsImV4cCI6MTczNTkxNTgzNCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA0Mzc5ODM0LCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjEvNC8yMDI0IDI6MjE6NTYgUE0iLCJjdXJyZW50LWxvZ2luIjoiMS80LzIwMjQgMjo1MDozNCBQTSIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.ikBTbijK5ERYN8kcjEUZWYToWQCXzrLm7eiCZYHAo8FXEypknmESO7YHrH-k36WOWNf3F1E-uKGtCfjMBuQqa_pYPHJKMBpWQuErZ2-HGLXj7YvIBPwbQtTuTebVbFcaeDJv1-wZo9RnxEOgvJQntDrSEP_Jwv8jxM05zckBNR7GwqGIxttO0H4E6-4oInej4F7pTT0LThegvBl83lOCUrhBwFV-vYM4SnpfSgmgnG8NpIv9M2OoiaXFEy8uN36WGh8aH0DW19COpqARskmL0Hje8W435X6V5x-3e1AZFlfKTor81dA_mKsqlSjFX-1UNKxHS7kwaNG-aBAK4iB3Tw';
				}
			}
			else if($module == 'event'){
				$ebt_api_url = $options['evt_api_url'];//"https://engagifii-preview4-event.azurewebsites.net/api/1.0";
				$ebt_tenant_code = $options['evt_tenant_code'];
					$peopleToken = $_SESSION['accesstoken'];
				$authentication = 'authorization: Bearer ' . $peopleToken;
				//$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDU2NTA4NDMsImV4cCI6MTcwNTY1ODA0MywiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA1NjUwODQyLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjAxLzE5LzIwMjQgMDc6MTk6NDUiLCJjdXJyZW50LWxvZ2luIjoiMDEvMTkvMjAyNCAwNzo1NDowMyIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.HxfY9xZMXnGraPomQWWi88FvTs4sgd-78z34D9yHbjaSA_DdPE2EB5LRp-NzEuG7w4slIq_asrraMonGITakg0Q2qlcqXmfsv3pKDsnWmsY09IBSJfFLUmVUL8Vszsfq1GGDjfKC7QDnejRb9LViKZfeX-hWcP4LB9ALytJXvDnKU_4IEFOMrIdUbjFPHwQ9VlfX2uCPiJREPCu9O0YsmtOjlCZxBrzqe7X9nK7TwOGOxZprjgA3FHvGpUqa1utsaYVIS_Qp8GtmWoOFn0vCZQhBEPyyyD8VTZ-KM0K0b_xZwMJhHXadiQXBAlfE7vr_yyl0MDb_9J_geSYug6Ewuw';
				} else if($module == 'mycourses'){
				//$ebt_api_url = $options['ebt_api_url'];
				$ebt_api_url = 'https://engagifii-preview4-engagifiireport.azurewebsites.net/api/v1';
				$ebt_tenant_code ['tenant_code'] = 'psba';
					$peopleToken = $_SESSION['accesstoken'];
				$authentication = 'authorization: Bearer ' . $peopleToken;
				//$authentication = 'Authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDQzNzk4MzQsImV4cCI6MTczNTkxNTgzNCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcWEtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA0Mzc5ODM0LCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjEvNC8yMDI0IDI6MjE6NTYgUE0iLCJjdXJyZW50LWxvZ2luIjoiMS80LzIwMjQgMjo1MDozNCBQTSIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.ikBTbijK5ERYN8kcjEUZWYToWQCXzrLm7eiCZYHAo8FXEypknmESO7YHrH-k36WOWNf3F1E-uKGtCfjMBuQqa_pYPHJKMBpWQuErZ2-HGLXj7YvIBPwbQtTuTebVbFcaeDJv1-wZo9RnxEOgvJQntDrSEP_Jwv8jxM05zckBNR7GwqGIxttO0H4E6-4oInej4F7pTT0LThegvBl83lOCUrhBwFV-vYM4SnpfSgmgnG8NpIv9M2OoiaXFEy8uN36WGh8aH0DW19COpqARskmL0Hje8W435X6V5x-3e1AZFlfKTor81dA_mKsqlSjFX-1UNKxHS7kwaNG-aBAK4iB3Tw';
				} else if($module == 'dashboard'){
					$peopleToken = $_SESSION['accesstoken'];
				$ebt_api_url = 'https://engagifii-preview4-crm.azurewebsites.net/api/v1';
				$ebt_tenant_code ['tenant_code'] = 'psba';
				$authentication = 'authorization: Bearer ' . $peopleToken;
				//$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDcyMDgxMTQsImV4cCI6MTczODc0NDExNCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA3MjA4MTEzLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjAyLzA2LzIwMjQgMDg6MTY6MzUiLCJjdXJyZW50LWxvZ2luIjoiMDIvMDYvMjAyNCAwODoyODozNCIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.Nrfe03f61j8sT2PwjRoA4LIf-EnkT__WMdiEbV0xr2kcpAWdbeAJlzMIGkms7Cge3EQ-DcS_yaaGSFtzjt83pvCULp9a75OMRyklMhEPxpT1AIlmxgLQnYGEpsCvYfYBKeTT8bX2kz3KGrNYsSf9f0-J4_nhF6iIeH_DROP3M2h8NcbyRc4wLxe00X8_-3vwO2lUIjQL6TYcEppIKdNRqyJ-odOP8dwfW2WYZJxKGPSRwEbWVTCnQyWQ03Y9hf7caAKAD8kI-6Xz7zPiT03ZBLZFHH99dBDCmfiGPIs0fQdlPqjbYGZDE0uhVvuJTwtNP3GqTQ93ai5Hz5pKfWsT0g';
				} else if($module == 'reports'){
				$ebt_api_url = 'https://engagifii-preview4-engagifiireport.azurewebsites.net/api/v1';
				$ebt_tenant_code ['tenant_code'] = 'psba';
					$peopleToken = $_SESSION['accesstoken'];
				$authentication = 'authorization: Bearer ' . $peopleToken;
//$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDcyMDgxMTQsImV4cCI6MTczODc0NDExNCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA3MjA4MTEzLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjAyLzA2LzIwMjQgMDg6MTY6MzUiLCJjdXJyZW50LWxvZ2luIjoiMDIvMDYvMjAyNCAwODoyODozNCIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.Nrfe03f61j8sT2PwjRoA4LIf-EnkT__WMdiEbV0xr2kcpAWdbeAJlzMIGkms7Cge3EQ-DcS_yaaGSFtzjt83pvCULp9a75OMRyklMhEPxpT1AIlmxgLQnYGEpsCvYfYBKeTT8bX2kz3KGrNYsSf9f0-J4_nhF6iIeH_DROP3M2h8NcbyRc4wLxe00X8_-3vwO2lUIjQL6TYcEppIKdNRqyJ-odOP8dwfW2WYZJxKGPSRwEbWVTCnQyWQ03Y9hf7caAKAD8kI-6Xz7zPiT03ZBLZFHH99dBDCmfiGPIs0fQdlPqjbYGZDE0uhVvuJTwtNP3GqTQ93ai5Hz5pKfWsT0g';
				} else if($module == 'awards'){
				$ebt_api_url = 'https://engagifii-preview4-tna.azurewebsites.net/api/v1';
				$ebt_tenant_code ['tenant_code'] = 'psba';
					$peopleToken = $_SESSION['accesstoken'];
				$authentication = 'authorization: Bearer ' . $peopleToken;
//$authentication = 'authorization: Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjczQ0Q4NERGRUJGQzk4NUU4RUZGOTU0QjY2NTg0OEFBMTYzNDExNkIiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJjODJFMy12OG1GNk9fNVZMWmxoSXFoWTBFV3MifQ.eyJuYmYiOjE3MDU5MjEwNDAsImV4cCI6MTcwNTkyODI0MCwiaXNzIjoiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQiLCJhdWQiOlsiaHR0cHM6Ly9lbmdhZ2lmaWktcHJldmlldzQtaWRlbnRpdHkuYXp1cmV3ZWJzaXRlcy5uZXQvcmVzb3VyY2VzIiwiVXNlcnNBUEkiLCJBY2NyZWRpdGF0aW9uQVBJIiwiQmlsbHRyYWNraW5nQXBpIiwiQ29tbWVudEFwaSIsIk5vdGVzQXBpIl0sImNsaWVudF9pZCI6Im5nLkVuZ2FnaWZpaVVJIiwic3ViIjoiNzE5ZTgwOTgtOTg0YS00OTBmLThiNWEtM2M5MTk0ZDk2NzhmIiwiYXV0aF90aW1lIjoxNzA1OTIxMDQwLCJpZHAiOiJsb2NhbCIsInNzLXBpZCI6IiIsInBpY3R1cmUiOiIiLCJwaWN0dXJlLWljb24iOiIiLCJwaWN0dXJlLXNtYWxsIjoiIiwicGljdHVyZS1pY29uIjoiIiwiZ2l2ZW5fbmFtZSI6IkNyZXNjZXJhbmNlIiwiZmFtaWx5X25hbWUiOiJBZG1pbiIsImVtYWlsIjoiYWRtaW5AY3Jlc2NlcmFuY2UuY29tIiwibGFzdC1sb2dpbiI6IjAxLzIyLzIwMjQgMTA6NTQ6NDciLCJjdXJyZW50LWxvZ2luIjoiMDEvMjIvMjAyNCAxMDo1NzoyMCIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCIsIlVzZXJzQVBJIiwiQWNjcmVkaXRhdGlvbkFQSSIsIkJpbGx0cmFja2luZ0FwaSIsIkNvbW1lbnRBcGkiLCJOb3Rlc0FwaSJdLCJhbXIiOlsicHdkIl19.g-LxFKPj00MXMUkupYlN1trw3i_mL-0u3kGlQ31qli5VzQI-NFlnX5skewt-9OJKPOfItfMbM8Rdt5_qf4noFk3WEExwGjSHbY1C8RJRqCFbf9WybM2kqWk9A3YFy9ZbZD5XUd4lojb3cZQdSpMdcBvlMhTTcf33xhgsrf4Uy2BT1W8SP4ukZb6AqvkSeCtCrLEaUoWwn6kGcFjiOxycbdn1x9Gzy5C0K6eUoWi1Q2oeobDhgKXiRVSXuFn7c62N0U37t-1AnD2xXDPaJjm-mpWmx1OX1ZZqhb73Tolt_S5dwIzigI7xhSzviecp90mrE3Z6IkHQmt11nkjSqnLHEQ';
				}
			$tenant_code = $ebt_tenant_code ['tenant_code'];
			$curl = curl_init();
			curl_setopt_array($curl, array(  

				CURLOPT_URL 			=> $ebt_api_url."/".$requestUrl, //"https://engagifii-preview4-event.azurewebsites.net/api/1.0/public/EventColumnList", 
				CURLOPT_RETURNTRANSFER 	=> true,
				CURLOPT_ENCODING 		=> "",   
				CURLOPT_CUSTOMREQUEST 	=> $requestType,
				CURLOPT_POSTFIELDS 		=> json_encode($requestData),
				CURLOPT_HTTPHEADER 		=> array(
					"cache-control: no-cache",
					"content-type: application/json",   
					"tenant-code:".$tenant_code, 
					$authentication
				),

			));
			if($tenant_code!=""){
							$response = curl_exec($curl);
				}
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				$prepareApiResponse['api_status'] 	= false;
				$prepareApiResponse['api_response'] = false;
				$prepareApiResponse['api_error'] 	= $err;  
			} else {  
				$prepareApiResponse['api_status'] 	= true;
				$prepareApiResponse['api_response'] = $response;  
			}
		}
		return $prepareApiResponse; 
		
	}


	/*
		send Engagifii API POST request
	*/

	protected function submitApiRequestWithGet($requestUrl,$requestData, $module)
	{
		$options 			= get_option( 'ebt_api_settings' );
		$requestType		="GET";
		if($module == 'legislation')
		{
			$ebt_api_url = $options['lbt_api_url'];
			$ebt_tenant_code = $options['lbt_tenant_code'];
		}
		else if($module == 'endorsement'){
			$ebt_api_url = $options['ebt_api_url'];
			$ebt_tenant_code = $options['ebt_tenant_code'];
		}
	
		$tenant_code 		= $ebt_tenant_code ['tenant_code'];


		$prepareApiResponse = array();
		$curl = curl_init();
		curl_setopt_array($curl, array(  
			CURLOPT_URL => $ebt_api_url."/".$requestUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",   
			CURLOPT_CUSTOMREQUEST => $requestType,
			CURLOPT_POSTFIELDS => json_encode($requestData),
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",   
				"tenant-code:".$tenant_code,   
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			$prepareApiResponse['api_status'] = false;
			$prepareApiResponse['api_response'] = false;
			$prepareApiResponse['api_error'] = $err;  
		} else {
			$prepareApiResponse['api_status'] = true;
			$prepareApiResponse['api_response'] = $response;  
		}
		return $prepareApiResponse;
	}

	  public function _popOverInstructorData($id, $instructorData){
	  	$options = get_option('ebt_api_settings');
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];

         	


        $rowName = array();

        $popOverHtml = '<span id="span_' . $id . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="searchbox_' . $id . '">';
        $subItems = "";

        foreach ($instructorData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->fullName;
            
            if($rowData->thumbnailUrl)
            {
                if (filter_var($rowData->thumbnailUrl, FILTER_VALIDATE_URL)) { 
                    $instructor_img = $rowData->thumbnailUrl;
                }
                else
                {
                    $instructor_img = $tenant_url.$rowData->thumbnailUrl;
                }
                
            }
            else
            {
                $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

           	}

            
            $subItems .= ' <option value="' . $rowData->fullName . '" data-capital="' . $rowData->fullName . '"  >' . $rowData->fullName . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';

        $vars = "
                       <script>
                       $(function() {

                            instructor_{$id} = $('#searchbox_{$id}').select2({
                                templateResult: function(item) {
                                    return format(item,   false);
                                }
                                });

                                $(document).on('click', '.instructor_{$id}', function () {

                                    instructor_{$id}.select2('open');
                                    setTimeout(function(){ __addExtraDiv('Instructors')},100);
                                    });


                                    });
                                    </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;

    }

//Events Speakers Popover Data

public function _popOverSpeakerData3($id, $instructorData){
	$options = get_option('ebt_api_settings');
  $endorsement_api_url = $options['ebt_api_url'];
  $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
  $rowName = array();
  $popOverHtml =  dd_header('Speakers','Search speakers..');
  $subItems = "";

  foreach ($instructorData as $key => $rowData) {
	  
	  $rowName[$rowData->id] = $rowData->fullName;
	  
	  if($rowData->thumbnailUrl)
	  {
		  if (filter_var($rowData->thumbnailUrl, FILTER_VALIDATE_URL)) { 
			  $instructor_img = $rowData->thumbnailUrl;
		  }
		  else
		  {
			  $instructor_img = $tenant_url.$rowData->thumbnailUrl;
		  }
		  
	  }
	  else
	  {
		  $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

		 }

	  
            $class='';
            if($li%2==1){
			$class='bg-light';	
			}
			$subItems .= '<li class="px-2 py-1 border-bottom  small '.$class.'">' . $rowData->fullName . '</li>';
			$li++;
  }

  $popOverHtml .= $subItems;
        $popOverHtml.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';

  $vars = "";

  $popOverHtml .= '</ul></span>';
  $popOverHtml .= '</div>';

  $popOverHtml .= '</div>';
  $popOverHtml .= '</div>';
  $popOverHtml .= '</div> ';

  return $popOverHtml . $vars;

}
//Events Speaker data ends
	  public function _popOverInstructorData1($id, $instructorData){
	  	$options = get_option('ebt_api_settings');
        $endorsement_api_url = $options['ebt_api_url'];
        $tenant_url          = $options['ebt_tenant_code']['engagifii_url'];
        $rowName = array();
		$popOverHtml =  dd_header('Instructors','Search Instructors..');
        $subItems = "";
		$li=1;
        foreach ($instructorData as $key => $rowData) {
            
            $rowName[$rowData->id] = $rowData->fullName;
            
            if($rowData->thumbnailUrl)
            {
                if (filter_var($rowData->thumbnailUrl, FILTER_VALIDATE_URL)) { 
                    $instructor_img = $rowData->thumbnailUrl;
                }
                else
                {
                    $instructor_img = $tenant_url.$rowData->thumbnailUrl;
                }
                
            }
            else
            {
                $instructor_img = ENGAGIFII_ASSETS_URL.'/images/user-default.png';

           	}

            $class='';
            if($li%2==1){
			$class='bg-light';	
			}
			$subItems .= '<li class="px-2 py-1 border-bottom  small '.$class.'">' . $rowData->fullName . '</li>';
			$li++;
        }
		
        $popOverHtml .= $subItems;
        $popOverHtml.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';

      	$vars = "";
        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;

    }

    public function _popOverClass($id, $classData){
    	$rowName = array();
       
        $popOverHtml = '<span id="span_' . $id . '"  style="opacity:0;display:block;height:0;"> <select class="form-control" id="classbox_' . $id . '">';
        $subItems = "";
        
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->name] = $rowData->sectionName;
            $classTime = '';
            if($rowData->sectionName)
                $classTime = $rowData->sectionName;
            
            $subItems .= ' <option value="' .  $rowData->sectionName . '" data-capital="' .  $rowData->sectionName . '" >' . $rowData->sectionName . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
         $vars = "<script>
                    $(function() {
                        class_{$id} = $('#classbox_{$id}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.class_{$id}', function () {
                            class_{$id}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Classes')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }
    public function _popOverClass1($id, $classData){
    	$rowName = array();
       
        $popOverHtml = '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center mb-0 pb-2">Classes</h6><div class="px-2 border-bottom pb-2"><input class="form-control form-control-sm bg-light search-dropdown" placeholder="Search Classes.."/></div>';
        $subItems = "";
        $li=1;
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->name] = $rowData->sectionName;
            $classTime = '';
            if($rowData->sectionName)
                $classTime = $rowData->sectionName;
            $class='';
            if($li%2==1){
			$class='bg-light';	
			}
			$subItems .= '<li class="px-2 py-1 border-bottom  small '.$class.'">' .$rowData->sectionName . '</li>';
			$li++;
            
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '<span class="px-2 py-1 text-center   small d-none">No results found!</span></div>';
        
		$vars = "";
        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }
     public function _popOverClassesDate($courseid, $classData){

        $rowName = array();
       
        $popOverHtml = '<span id="span_' . $courseid . '"  style="opacity:0;height:0;display:block;"> <select class="form-control" id="classbox_' . $courseid . '">';
        $subItems = "";
        
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->sequenceNumber] = $rowData->sequenceNumber;
            $classTime = '';
            if($rowData->sessionDay)
                $classTime = date('M d Y', strtotime($rowData->sessionStartTime)).' At '.date('H:i A', strtotime($rowData->sessionStartTime)).' - '.date('H:i A', strtotime($rowData->sessionEndTime));
            
            $subItems .= ' <option value="' .  date('M d Y', strtotime($rowData->sessionStartTime)) . '" data-capital="' .  date('M d Y', strtotime($rowData->sessionStartTime)) . '" >' . $classTime . '</option>';
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</select>';
        
        $vars = "<script>
                    $(function() {
                        class_{$courseid} = $('#classbox_{$courseid}').select2({
                            templateResult: function(item) {
                                return format(item,  false);
                            }
                        });

                        $(document).on('click', '.class_{$courseid}', function () {
                            class_{$courseid}.select2('open');
                            setTimeout(function(){ __addExtraDiv('Associated Classes')},100);
                        });
                    });
                </script>";

        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }
     public function _popOverClassesDate1($courseid, $classData){

        $rowName = array();
       
        $popOverHtml = '<div class="dropdown-menu dropdown-menu-right td-dropdown pb-0 pt-2" aria-labelledby="dropdownMenuButton" ><h6 class="text-center border-bottom mb-0 pb-3">Associated Classes</h6>';
        $subItems = "";
        $li=1;
        foreach ($classData as $key => $rowData) {
            
            $rowName[$rowData->sequenceNumber] = $rowData->sequenceNumber;
            $classTime = '';
            if($rowData->sessionDay)
                $classTime = date('M d Y', strtotime($rowData->sessionStartTime)).' At '.date('H:i A', strtotime($rowData->sessionStartTime)).' - '.date('H:i A', strtotime($rowData->sessionEndTime));
            $class='';
            if($li%2==1){
			$class='bg-light';	
			}
			$subItems .= '<li class="px-2 py-1 border-bottom d-flex align-items-center small '.$class.'"><img style="max-width:25px" src="'. ENGAGIFII_ASSETS_URL.'/images/class.png' .'" class="img-fluid mr-2"/>' . $classTime . '</li>';
			$li++;
        }

        $popOverHtml .= $subItems;
        $popOverHtml.= '</div>';
        
		$vars = "";
        $popOverHtml .= '</ul></span>';
        $popOverHtml .= '</div>';

        $popOverHtml .= '</div>';
        $popOverHtml .= '</div>';
        $popOverHtml .= '</div> ';

        return $popOverHtml . $vars;
    }


	/*
	* Endorsement General Tab API	
	*/
	public function getEndDetails($endid)
   	{

      $postData = array();  
      $apiUrl = 'Public/getAwardById/'.$endid;
      return $this->submitApiRequestWithGet($apiUrl,$postData, 'endorsement');
   	}

   /* Course Curriculam Api */
   	public function getCurriculam($endid)
   	{

      $postData = array();  
      $apiUrl = 'Awards/'.$endid.'/GetPublicAwardCourseCurriculum/';
      return $this->submitApiRequestWithGet($apiUrl,$postData, 'endorsement');
   	}

   	/*
   	*	Legislation API functions
   	*	Since V1.0.0
   	* 	Description: fetch billing details 
   	*/

   	/* Tracking Level Api */

	public function getTrackingLevels()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/trackinglevels/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/* House Committe Api */

	public function houseCommiittee()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/committees/house/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/* Senate  Committe Api */

	public function senateCommiittee()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/committees/senate/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/* Sponsor  Api */

	public function sponsorList()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/sponsors/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/* Bill Type  Api */

	public function billType()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/billtypes/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/*Bill Session API */
	
	public function sessions()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/sessions/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

/*Legislative Reports API */
	
public function legislativeReports()
{
$postData = array();
$apiUrl = 'legislative/bills/report/public-dashboard/getAllReportType/';
return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
}

/*Legislative Reports API */

public function reportlist($id)
{
$postData = array();
$apiUrl = 'legislative/bills/report/public-dashboard/getReportByReportTypeId/'.$id;
//return $apiUrl;
return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
}


	/* Last Action Type  Api */

	public function lastAction()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/lastactions/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/* Status Api */

	public function statusFilter()
	{
	$postData = array();
	$apiUrl = 'legislative/public-bills/status/';
	return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation'); 
	}

	/* Bill Detail Page Api */

	public function getBillDetails($billid)
	{

	  $postData = array();  
	  $apiUrl = 'legislative/public-bills/'.$billid.'/detail/';
	  return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	}

	/* Version Api */
	public function getVersion($billid)
	{

	  $postData = array();  
	  $apiUrl = 'legislative/public-bills/'.$billid.'/version/';
	  return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	}


	/* Action History Api */
	public function getActionHistory($billid)
	{

	  $postData = array();  
	  $apiUrl = 'legislative/public-bills/'.$billid.'/actionhistory/';
	  return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	}


	/* Action Quick Link Api */
	public function getQuickLinks($billid)
	{

	  $postData = array();  
	  $apiUrl = 'legislative/public-bills/'.$billid.'/source/';
	  return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	}

	/* Staff Analysis Api */
	public function staffAnalysis($billid){
	  $postData = array();
	  $apiUrl = 'legislative/public-bills/'.$billid.'/analysis/';
	  return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	}

	/* public Analysis Api */
	public function publicAnalysis($billid){
		$postData = array();
		$apiUrl = 'legislative/public-bills/'.$billid.'/maco/publicanalysis'; 
		return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	  }
	  
	  
	  
	/* Staff Analysis Api */
	public function votesrollCall($billid){
	  $postData = array();
	  $apiUrl = 'legislative/public-bills/'.$billid.'/rollcall/';
	  return $this->submitApiRequestWithGet($apiUrl,$postData, 'legislation');
	}


	public function awardDateFilter($date){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Award/GetMinMaxAwardDate/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'courses');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
	public function engagifiiProfile($tenant, $emailId){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'People/GetLoggedInUserDetail';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'dashboard');
		$responseArray = json_decode($response['api_response'], true);
		return $response;
	}
	public function engagifiiProfileAwardsCount($profileId){
		$postData='{}';
		$responseArray = array();
		$apiUrl = 'Awards/AwardsCertificationsByPeopleCount/'.$profileId;
		$response =  $this->submitApiRequest($apiUrl, json_decode($postData), 'POST', 'awards');
		$responseArray = json_decode($response['api_response'], true);
		return $response;
	}
	public function engagifiiProfileAwards($profileId,$awardDataCount){
		$postData='{"itemCount":'.$awardDataCount.',"pageNumber":1,"sortBy":"name","sortDirection":"asc","filterBody":{}}';
		$responseArray = array();
		$apiUrl = 'Awards/AwardsCertificationsByPeople/'.$profileId;
		$response =  $this->submitApiRequest($apiUrl, json_decode($postData), 'POST', 'awards');
		$responseArray = json_decode($response['api_response'], true);
		return $response;
	}
	public function creditEarnedCount($profileId){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'registration/GetCreditsByParticipantId/'.$profileId;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'awards');
		$responseArray = json_decode($response['api_response'], true);
		return $response;
	}

//public official API
	public function publicOfficial(){

		$postData='{}';
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/elected/officials-all-tabs-list';
		$response =  $this->submitApiRequest($apiUrl, json_decode($postData), 'POST', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
//public official residence API
	public function poResidence(){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/residence-list';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
//public official district API
	public function poDistrict(){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/districtname-list';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
//public official party API
	public function poParty(){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/political-party';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
//public official role API
	public function poRole(){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/legislative-role';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
//public official committee API
	public function poComittee(){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/committee-list';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
//public official county API
	public function poCounty(){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'legislative/public-bills/county-list';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function eventDateFilter($date){
//alert($date);
		$postData=array();
		$responseArray = array();
		$apiUrl = 'event/GetMinMaxEventDate/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'event');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
	//event/GetMinMaxEventDate

	/*Get all Award Tags */
	public function awardAllTags($date){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Award/GetAllTags/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'endorsement');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	/*Get all Events Tags */
	public function eventsAllTags(){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'public/tags/1/1';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'event');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function eventTypes($date){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'public/event-type';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'event');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function eventLocation(){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'public/venues';
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'event');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	
	public function courseAllClasses($date) :array{
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Course/GetAllSectionNames/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'courses');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
	
	public function courseAllTags($date) :array{
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Course/GetAllCourseTags/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'courses');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}
	public function courseAllInstructors($date) :array{

		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Course/GetAllClassInstructors/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'courses');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function courseDateFilter($date){

		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Course/GetMinMaxCourseClassDate/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'courses');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function classAllInstructors($date):array{
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Class/GetAllClassInstructors/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'classes');
		if($response!=null) {
		$responseArray = json_decode($response['api_response'], true);
		}
		return $responseArray;

	}

	public function classdateFilters($date){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Class/GetMinMaxClassDate/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function classRegDateFilters($date){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Class/GetMinMaxClassRegDate/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function getAllClassCourses($date){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Class/GetAllClassCourses/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function getCreditHoursFilter($date){
		$postData=array();
		$responseArray = array();
		$apiUrl = 'Public/Class/GetCreditHoursRange/'.$date;
		$response =  $this->submitApiRequest($apiUrl, $postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response'], true);
		return $responseArray;
	}

	public function getCourseDetailsByID($id)
	{
		$postData = array();
		$responseArray = array();
		$apiUrl = 'Public/GetCourseById/'.$id;
		$response= $this->submitApiRequest($apiUrl,$postData, 'GET', 'courses');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;

	}
/*Event Detail */
public function getEventDetailsByID($id)
{
	$postData = array();
	$responseArray = array();
	$apiUrl = 'Public/'.$id;
	$response= $this->submitApiRequest($apiUrl,$postData, 'GET', 'event');
	$responseArray = json_decode($response['api_response']);
	return $responseArray;

}

//end here
	
	public function getCourseDocument($id, $title){
		$postData = array();
		$responseArray = array();
		$apiUrl = 'section/child/id';
		$postData['childSectionSourceId'] = $id;
		$postData['section']              = 'Courses';
		$postData['parentSection']        = 'TrainingAndAccreditation';
		$postData['childSectionSourceTitle'] = $title;
		$response= $this->submitApiRequest($apiUrl,$postData, 'POST', 'document');
		$section_id = json_decode($response['api_response']);
		$responseArray = $this->getDocumentDetails($section_id);
		return $responseArray;
		
	}

	public function getDocumentDetails($id){
		$postData = array();
		$responseArray = array();
		$apiUrl  = 'media/list/'.$id;
		$postData['sectionId'] = $id;
		$postData['pageNumber'] = 1;
		$postData['pageSize']  = 25;
		$postData['sortBy']    = 'Added';
		$response= $this->submitApiRequest($apiUrl,$postData, 'POST', 'document');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;

	}
	public function getRelatedClassBycourse($id, $count){
	
		$postData = array();
		$apiUrl = 'Public/RelatedClassesPublic/'.$id;
		$postData['courseId'] = $id;
		$postData['itemCount'] = $count;
		$postData['pageNumber'] = '1';
		$postData['sortDirection'] = 'desc';
		$response= $this->submitApiRequest($apiUrl,$postData, 'POST', 'courses');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;

	}
	public function getEventRelatedClassBycourse($id, $count){
	
		$postData = array();
		$apiUrl = 'Public/getEventRelatedClassBycourse/'.$id;
		$postData['courseId'] = $id;
		$postData['itemCount'] = $count;
		$postData['pageNumber'] = '1';
		$postData['sortDirection'] = 'desc';
		$response= $this->submitApiRequest($apiUrl,$postData, 'POST', 'event');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;

	}

	public function getRelatedClassByClass($courseId, $classId, $count)
	{
		$postData = array();
		$apiUrl = 'Public/RelatedClassesPublic/'.$courseId.'?classId='.$classId;
		$postData['courseId'] = $courseId;
		$postData['classid'] = $classId;
		$postData['itemCount'] = $count;
		$postData['pageNumber'] = '1';
		$postData['sortDirection'] = 'desc';
		$response= $this->submitApiRequest($apiUrl,$postData, 'POST', 'classes');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
	}

	public function getClassDetailsByID($id)
	{
		$postData = array();
		$responseArray = array();
		$apiUrl = 'Public/getClassById/'.$id;
		$response= $this->submitApiRequest($apiUrl,$postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
	}

	public function getCertifiedInstructor($id)
	{
		$postData = array();
		$responseArray = array();
		$apiUrl = 'Public/'.$id.'/CertifiedInstructors';
		$response= $this->submitApiRequest($apiUrl,$postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
	}

	public function getOtherInstructor($id)
	{
		$postData = array();
		$responseArray = array();
		$apiUrl = 'Public/'.$id.'/AllOtherInstructors';
		$response= $this->submitApiRequest($apiUrl,$postData, 'GET', 'classes');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
	}

	
	public function legislationTagsFilter(){
        $postData = array();
        $responseArray = array();
        	$apiUrl= 'legislative/public-bills/filter/tags';
        $response = $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
        $responseArray = json_decode($response['api_response']);
        return $responseArray;
    }

	public function legislationAssignToFilter(){

		$postData = array();
		$responseArray = array();
		$apiUrl= 'legislative/public-bills/filter/billusers';
		$response = $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
	}

	public function legislationGroupsFilter(){
		$postData = array();
		$responseArray = array();
		$apiUrl= 'legislative/public-bills/filter/groups';
		$response = $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
			
	}

	public function legislationAssignToTagFilter(){
		$postData = array();
		$responseArray = array();
		$apiUrl= 'legislative/public-bills/filter/billusertags';
		$response = $this->submitApiRequest($apiUrl, $postData, 'GET', 'legislation');
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
			
	}

	public function legislationBillTabSequence(){
		$getData = array();
		$responseArray = array();
		$apiUrl        = 'billdetail-tabs/GetBillDetailTabs';
		$response = $this->submitApiRequest($apiUrl, $getData, 'GET', 'legislation-auth');
		
		$responseArray = json_decode($response['api_response']);
		return $responseArray;
	}

}