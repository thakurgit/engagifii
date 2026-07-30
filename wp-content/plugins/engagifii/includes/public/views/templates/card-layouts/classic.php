<?php
/**
 * Classic Card Layout Template
 * 
 * Traditional card with image on top and details below
 * Reusable for Organizations, Group Members, etc.
 */

defined('ABSPATH') || exit;
?>

<style>
/* Classic Layout Styles */
.org-card-classic {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.org-card-classic:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
}

.org-card-classic .img-default {
    font-size: 80px;
    display: block;
    text-align: center;
}

.org-card-classic .org-card-logo-wrapper {
    width: 100%;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 6px;
}

.org-card-classic .org-card-logo {
    max-width: 100%;
    max-height: 160px;
    width: auto;
    height: auto;
    object-fit: contain;
}

.org-card-classic .card-img-top {
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.org-card-classic .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.org-card-classic .card-text {
    font-size: 0.9rem;
    color: #6c757d;
}

.org-card-classic .card-text .font-weight-bold {
    color: #495057;
}
h5{
    margin-top: 0px;
}
</style>

<script>
// Classic Layout Rendering Function
function renderClassicLayout(data, container) {
    data.forEach(function(org) {
        var orgDefaultImg = '<?php echo esc_url( ENGAGIFII_ASSETS_URL . "/images/org-list-grey.png" ); ?>';
        var orgImgSrc = isValidUrl(org.imageThumbUrl) ? org.imageThumbUrl : orgDefaultImg;
        var orgPhoto = '<div class="org-card-logo-wrapper text-center border-bottom">' +
            '<img src="' + orgImgSrc + '" class="img-fluid org-card-logo" alt="' + org.name + '">' +
            '</div>';

       var fieldValues = buildFieldValues(org);
       var cardBody = '<h5 class="card-title">' + fieldValues.name + '</h5><hr style="margin-top:4px;margin-bottom:6px;border-top:1px solid rgba(0,0,0,.12); width:20%">';

       // Render "Contact Name" first if present in selected columns
       // Matched by stable fieldId so any rename (e.g. "Key Person") still works
       var contactNameCol = organizationGridCols.find(function(c) {
           return (c.fieldId && c.fieldId.toUpperCase() === '265099E0-4287-4380-8265-77FF0AFD10B1') ||
                  c.colClass === 'primarycontactname' || c.colClass === 'contactname';
       });
       if (contactNameCol) {
           var contactVal = fieldValues[contactNameCol.colClass];
           if (contactVal !== undefined && contactVal !== '--') {
               cardBody += '<p class="card-text mb-1 mt-0"><strong style="color:#202b5d !important;">' + contactVal + '</strong></p>';
           }
       }

       // Render "Website" as a clickable link (no label prefix)
       // Matched by stable fieldId
       var websiteCol = organizationGridCols.find(function(c) {
           return (c.fieldId && c.fieldId.toUpperCase() === '6440F5F6-6F2F-49FC-8840-76E9D2EBAC00') ||
                  c.colClass === 'website';
       });
       if (websiteCol) {
           // When guest masking is enabled for website, use the pre-masked HTML from fieldValues
           if (!isUserLoggedIn && orgGuestHiddenFields.indexOf('website') !== -1) {
               var maskedWebsite = fieldValues[websiteCol.colClass];
               if (maskedWebsite && maskedWebsite !== '--') {
                   cardBody += '<p class="card-text mb-1">' + maskedWebsite + '</p>';
               }
           } else {
               // Use the raw org.website URL only — fieldValues contains pre-formatted HTML and must NOT be used as a URL
               var wsRaw = org.website || '';
               // Fallback: check raw custom fields for the website fieldId when org.website is absent
               if (!wsRaw && websiteCol.fieldId) {
                   var wsCf = (org.customFields || []).find(function(cf) {
                       return cf.fieldId && cf.fieldId.toLowerCase() === websiteCol.fieldId.toLowerCase() && cf.fieldValue;
                   });
                   if (wsCf) wsRaw = wsCf.fieldValue;
               }
               if (wsRaw) {
                   var wsUrl = /^https?:\/\//i.test(wsRaw) ? wsRaw : 'https://' + wsRaw;
                   cardBody += '<p class="card-text mb-1">' +
                       '<a href="' + wsUrl + '" target="_blank" rel="noopener noreferrer" style="color:#007bff !important;">' +
                       '<span class="font-weight-bold" style="color:#007bff !important;">Visit Website</span></a>' +
                   '</p>';
               }
           }
       }

        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            var label = colObj.displayName;
             if (label === 'Locations') label = 'Location';
            if (label === 'Created On') label = 'Added';
            if (label === 'Modified On') label = 'Last Updated';
            if (label === 'Primary Email') label = 'Email';
            if (label === 'Phone Numbers') label = 'Phone';
            if (label === 'Total Members') label = 'Total/Active Members';

            if (col === 'name') return;
            if (contactNameCol && col === contactNameCol.colClass) return; // already rendered above
            if (websiteCol && col === websiteCol.colClass) return;         // already rendered above as link
            if (fieldValues[col] !== undefined) {
                cardBody += '<p class="card-text mb-1"><span class="font-weight-bold">' + label + ':</span> ' +
                    fieldValues[col] +
                    '</p>';
            }
        });

        cardBody += '<p class="card-text mb-0 mt-2 btn-detail">' +
            '<a href="' + organizationDetailLink + '?organizationId=' + org.id + '" class="btn btn-link btn-sm pl-0">View Detail</a>' +
            '</p>';

        var colsPerRow = (typeof orgClassicCardsPerRow !== 'undefined') ? orgClassicCardsPerRow : 4;
        var colClass = colsPerRow === 2 ? 'col-md-6' : (colsPerRow === 3 ? 'col-md-4' : 'col-md-3');

        var card = '<div class="' + colClass + ' mb-4" org-id="'+ fieldValues.id +'">' +
            '<div class="card h-100 shadow p-3 org-card-classic">' +
            orgPhoto +
            '<div class="card-body p-0 pt-2 group-card">' +
            cardBody +
            '</div></div></div>';
        container.append(card);
    });
}
</script>
