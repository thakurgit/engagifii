<?php 
$obj = new Engagifii_API();
$options = get_option('ebt_api_settings');
$tenant_url          = $options['lbt_tenant_code']['engagifii_url'];


if (filter_var($tenant_url, FILTER_VALIDATE_URL)) {
    $tenant = explode('.', parse_url($tenant_url, PHP_URL_HOST))[0];
} else {
    $tenant = $tenant_url;
}

//echo $tenant; // Output will be "gsba" in both cases

$reportsResponse = $obj->legislativeReports();
if(!$reportsResponse['api_response']){
		echo'<h5 class="text-center pt-5">Data not available</h5>';
		return;	
	}
$reportsResponses = json_decode($reportsResponse['api_response'], true);
$reportListData = [];
$site_url = site_url();

foreach ($reportsResponses as $value) {
    $reportListResponse = $obj->reportlist($value['reportTypeMasterId']);
    $reportListResponses = json_decode($reportListResponse['api_response'], true);
    $reportListData[$value['reportTypeMasterId']] = $reportListResponses;
}
?>


    <style>
        .tabs {
            display: flex;
			padding-bottom: 40px;
        }

        .tab {
            padding: 10px 20px;
            background-color: #f0f0f0;
            margin-right: 10px;
            cursor: pointer;
			border-radius: 10px;
			
        }

        .tab.active {
            background-color: #002473;
			color: white !important;
        }
		.tab.active a {
           color: white !important;
        }
		.tab a {
           color: #54595F !important;
        font-weight: 600;
    font-size: small;

        }

        .tab-content .tab-pane {
            display: none;
        }

        .tab-content .tab-pane.active {
            display: block;
        }
		
		img.img-viewdetail {
   			 height: 60%;
		}
    </style>

    <div class="tabs flex-wrap ">
        <?php
        $first = true;
        foreach ($reportsResponses as $value) {
            $isActive = $first ? 'active' : '';
            ?>
            <div class="tab mb-3 <?php echo $isActive; ?>" data-report-type-id="<?php echo $value['reportTypeMasterId']; ?>">
                <a href="javascript:void(0)">
                    <?php echo $value['name']; ?>
                </a>
            </div>
            <?php
            $first = false;
        }
        ?>
    </div>

    <div class="tab-content" id="reports-tabPane">
        <?php
        
        foreach ($reportsResponses as $value) {
             ?>
            <div class="tab-pane" id="report-tab-<?php echo $value['reportTypeMasterId']; ?>">
			<?php $tabData = $reportListData[$value['reportTypeMasterId']]; 
			foreach($tabData as $values){
				$name = $values['billReportName'];
				$id = $values['billReportId'];
				?>
				<a href="https://<?php echo $tenant; ?>.engagifii.com/public/lbt-report/<?php echo $id; ?>/schedule-false" style ="color: #002473;" target="_blank">
				<div class="row mb-2">
				       <div class="col-10">
                            <?php echo $name; ?>
                        </div>
                        <div class="col-2 text-right">
                            <span class="details">View Details </span><img src="<?php echo $site_url . '/wp-content/plugins/engagifii/assets/images/Union.png'; ?>" alt="..." class="img-viewdetail">
                        </div>
                    </div></a>
                    <div class="mb-2 border-bottom"></div>
				<?php
			}
			?>
                <!-- show Data here -->
            </div>
            <?php
        }
        ?>
    </div>

    <script>
      
        const reportData = <?php echo json_encode($reportListData); ?>;

        const tabs = document.querySelectorAll('.tabs .tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const reportTypeId = tab.getAttribute('data-report-type-id');
                const tabPane = document.getElementById(`report-tab-${reportTypeId}`);

               
                tabs.forEach(t => t.classList.remove('active'));
                document.querySelectorAll('#reports-tabPane .tab-pane').forEach(p => p.classList.remove('active'));

               
                tab.classList.add('active');
                tabPane.classList.add('active');

                
                //displayData(tabPane, reportTypeId);
            });
        });
			tabs[0].click();
		
        // Show "View Details" span on hover
        $(document).ready(function() {
			$(this).find('.details').hide();
            $('.row').hover(function() {
                $(this).find('.details').show();
            }, function() {
                $(this).find('.details').hide();
            });
        });
    
        // function displayData(tabPane, reportTypeId) {
        //     const data = reportData[reportTypeId];
        //     const ul = document.createElement('ul');

        //     if (data && data.length > 0) {
        //         data.forEach(item => {
        //             const li = document.createElement('li');
        //             const a = document.createElement('a');
        //             a.href = `https://engagifii.engagifii-preview6.com/public/lbt-report/${item['billReportId']}/schedule-false`;
        //             a.textContent = item['billReportName'];
        //             li.appendChild(a);
        //             ul.appendChild(li);
        //         });
        //     } else {
        //         const li = document.createElement('li');
        //         li.textContent = 'No records found.';
        //         ul.appendChild(li);
        //     }

            
        //     tabPane.innerHTML = '';
        //     tabPane.appendChild(ul);
        // }
    </script>
