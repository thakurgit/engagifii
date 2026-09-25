<?php
/**
 * Detailed Card Layout Template
 * 
 * Comprehensive card with all information displayed
 * Reusable for Organizations, Group Members, etc.
 */

defined('ABSPATH') || exit;
?>

<style>
/* Detailed Layout */
.org-card-detailed {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.org-card-detailed:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
}

.detailed-card-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
}

.detailed-img-wrapper {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
    overflow: hidden;
}

.detailed-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.detailed-card-icon {
    font-size: 36px;
    color: var(--engagifii-color);
}

.detailed-card-header .card-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    flex: 1;
}

.detailed-card-content {
    display: grid;
    gap: 12px;
}

.detail-row {
    display: flex;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    min-width: 140px;
    flex-shrink: 0;
}

.detail-value {
    color: #6c757d;
    flex: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .detailed-card-header {
        flex-direction: column;
        text-align: center;
    }
    
    .detail-row {
        flex-direction: column;
    }
    
    .detail-label {
        min-width: auto;
        margin-bottom: 4px;
    }
}
</style>

<script>
// Detailed Layout Rendering Function
function renderDetailedLayout(data, container) {
    var ctx = window.engagifiiGetOrgCardContext ? window.engagifiiGetOrgCardContext() : {};
    var helpers = window.engagifiiOrgCardHelpersFromCtx ? window.engagifiiOrgCardHelpersFromCtx(ctx) : {};
    var buildFieldValues = helpers.buildFieldValues;
    var getFieldLabel = helpers.getFieldLabel;
    var isValidUrl = helpers.isValidUrl;
    var organizationGridCols = ctx.organizationGridCols || [];
    var organizationDetailLink = ctx.organizationDetailLink || '';

    data.forEach(function(org) {
        var fieldValues = buildFieldValues(org);
        var orgDefaultImg = '<?php echo esc_url( ENGAGIFII_ASSETS_URL . "/images/org-list-grey.png" ); ?>';
        var orgPhoto = isValidUrl(org.imageThumbUrl)
            ? '<img src="' + org.imageThumbUrl + '" class="detailed-card-img" alt="' + org.name + '">'
            : '<img src="' + orgDefaultImg + '" class="detailed-card-img" alt="' + org.name + '">';
            
        var cardBody = '<div class="detailed-card-header">' +
            '<div class="detailed-img-wrapper">' + orgPhoto + '</div>' +
            '<h5 class="card-title">' + fieldValues.name + '</h5>' +
            '</div><hr><div class="detailed-card-content">';
            
        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            var label = getFieldLabel(colObj.displayName);
            if (col === 'name') return;
            if (fieldValues[col] && fieldValues[col] !== '--') {
                cardBody += '<div class="detail-row">' +
                    '<span class="detail-label">' + label + ':</span> ' +
                    '<span class="detail-value">' + fieldValues[col] + '</span>' +
                    '</div>';
            }
        });
        
        cardBody += '</div>';

        cardBody += '<p class="card-text mb-0 mt-2 btn-detail">' +
            '<a href="' + organizationDetailLink + '?organizationId=' + org.id + '" class="btn btn-link btn-sm pl-0">View Detail</a>' +
            '</p>';
        
        var card = '<div class="col-md-6 mb-4">' +
            '<div class="card h-100 shadow org-card-detailed">' +
            '<div class="card-body">' + cardBody + '</div>' +
            '</div></div>';
        container.append(card);
    });
}
</script>
