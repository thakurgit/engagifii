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

.org-card-classic .card-img-top {
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.org-card-classic .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.org-card-classic .card-text {
    font-size: 0.9rem;
    color: #6c757d;
}

.org-card-classic .card-text .font-weight-bold {
    color: #495057;
}
</style>

<script>
// Classic Layout Rendering Function
function renderClassicLayout(data, container) {
    data.forEach(function(org) {
        var orgDefaultImg = '<?php echo esc_url( ENGAGIFII_ASSETS_URL . "/images/org-list-grey.png" ); ?>';
        var orgPhoto = isValidUrl(org.imageThumbUrl)
            ? '<img src="' + org.imageThumbUrl + '" class="card-img-top mb-3" alt="' + org.name + '">'
            : '<img src="' + orgDefaultImg + '" class="card-img-top mb-3" alt="' + org.name + '">';

       var fieldValues = buildFieldValues(org);

       var cardBody = '<h5 class="card-title">' + fieldValues.name + '</h5>';
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
            if (fieldValues[col] !== undefined) {
                cardBody += '<p class="card-text mb-1"><span class="font-weight-bold">' + label + ':</span> ' +
                    fieldValues[col] +
                    '</p>';
            }
        });

        var card = '<div class="col-md-3 mb-4">' +
            '<div class="card h-100 shadow p-3 org-card-classic">' +
            orgPhoto + '<hr>' +
            '<div class="card-body p-0 pt-3 group-card">' +
            cardBody +
            '</div></div></div>';
        container.append(card);
    });
}
</script>
