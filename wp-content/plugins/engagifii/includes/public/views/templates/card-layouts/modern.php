<?php
/**
 * Modern Card Layout Template
 * 
 * Premium card design with image on top and gradient background
 * Reusable for Organizations, Group Members, etc.
 */

defined('ABSPATH') || exit;
?>

<style>
/* Modern Layout - Premium Card Design */
.modern-card-premium {
    background: linear-gradient(135deg, #1a3a52 0%, #2d5f7e 100%);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: none;
}

.modern-card-premium:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
}

.modern-card-image-container {
    width: 100%;
    height: 250px;
    overflow: hidden;
    position: relative;
}

.modern-card-premium .modern-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.modern-card-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
}

.modern-card-placeholder i {
    font-size: 80px;
    color: rgba(255, 255, 255, 0.3);
}

.modern-card-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
    color: #fff;
}

.modern-card-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 15px;
    line-height: 1.3;
}

.modern-card-subtitle {
    font-size: 1rem;
    margin-bottom: 12px;
    line-height: 1.5;
}

.modern-card-subtitle .highlight-text {
    color: #f4a261;
    font-weight: 700;
    font-style: italic;
}

.modern-card-subtitle .normal-text {
    color: rgba(255, 255, 255, 0.9);
    font-style: italic;
}

.modern-card-detail {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 12px;
    line-height: 1.5;
}

.modern-card-detail .detail-label {
    color: #f4a261;
    font-weight: 600;
    display: inline-block;
    margin-right: 5px;
}

.modern-card-detail .detail-value {
    color: rgba(255, 255, 255, 0.85);
}

.modern-card-detail a {
    color: #f4a261;
    text-decoration: none;
}

.modern-card-detail a:hover {
    color: #d4a26a;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-card-premium {
        margin-bottom: 20px;
    }
    
    .modern-card-image-container {
        height: 200px;
    }
}
</style>

<script>
// Modern Layout Rendering Function
function renderModernLayout(data, container) {
    data.forEach(function(org) {
        var fieldValues = buildFieldValues(org);
        var orgPhoto = isValidUrl(org.imageThumbUrl)
            ? '<img src="' + org.imageThumbUrl + '" class="modern-card-img" alt="' + org.name + '">'
            : '<div class="modern-card-placeholder"><i class="fa fa-building"></i></div>';
        
        var cardTitle = '';
        var detailsHtml = '';
        
        // Loop through configured columns and display them dynamically
        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            var label = getFieldLabel(colObj.displayName);
            
            if (fieldValues[col] === undefined || fieldValues[col] === '--') return;
            
            // First field becomes the title
            if (!cardTitle) {
                cardTitle = fieldValues[col];
            } else {
                // Remaining fields become details
                detailsHtml += '<p class="modern-card-detail">' +
                    '<span class="detail-label">' + label + ':</span> ' +
                    '<span class="detail-value">' + fieldValues[col] + '</span>' +
                    '</p>';
            }
        });
        
        var card = '<div class="col-md-3 mb-4">' +
            '<div class="card modern-card-premium">' +
            '<div class="modern-card-image-container">' +
            orgPhoto +
            '</div>' +
            '<div class="modern-card-content">' +
            '<h5 class="modern-card-title">' + (cardTitle || fieldValues.name) + '</h5>' +
            detailsHtml +
            '</div>' +
            '</div>' +
            '</div>';
        container.append(card);
    });
}
</script>
