<?php
/**
 * Minimal Card Layout Template
 * 
 * Clean and simple card with minimal details
 * Reusable for Organizations, Group Members, etc.
 */

defined('ABSPATH') || exit;
?>

<style>
/* Minimal Layout */
.org-card-minimal {
    padding: 30px 20px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.org-card-minimal:hover {
    border-color: var(--engagifii-color);
    transform: scale(1.02);
}

.minimal-card-icon {
    font-size: 60px;
    color: var(--engagifii-color);
    margin-bottom: 20px;
}

.org-card-minimal .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #212529;
}

.org-card-minimal .card-text {
    font-size: 0.9rem;
    color: #6c757d;
}
</style>

<script>
// Minimal Layout Rendering Function
function renderMinimalLayout(data, container) {
    data.forEach(function(org) {
        var fieldValues = buildFieldValues(org);
        var orgDefaultImg = '<?php echo esc_url( ENGAGIFII_ASSETS_URL . "/images/org-list-grey.png" ); ?>';
        var orgIcon = isValidUrl(org.imageThumbUrl)
            ? '<img src="' + org.imageThumbUrl + '" class="card-img-top" alt="' + org.name + '">'
            : '<img src="' + orgDefaultImg + '" class="card-img-top" alt="' + org.name + '">';
        
        var cardBody = '<h5 class="card-title text-center">' + fieldValues.name + '</h5>';
        var fieldCount = 0;
        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            var label = getFieldLabel(colObj.displayName);
            if (col === 'name' || fieldCount >= 3) return;
            if (fieldValues[col] !== undefined) {
                fieldCount++;
                cardBody += '<p class="card-text text-center mb-2">' + fieldValues[col] + '</p>';
            }
        });
        
        var card = '<div class="col-md-4 mb-4">' +
            '<div class="card h-100 shadow org-card-minimal text-center">' +
            orgIcon +
            '<div class="card-body">' + cardBody + '</div>' +
            '</div></div>';
        container.append(card);
    });
}
</script>
