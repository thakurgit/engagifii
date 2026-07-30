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
/* Modern Layout - Event-Style Design */
.modern-event-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 0;
    margin-bottom: 20px;
    transition: box-shadow 0.3s ease;
    padding: 30px 20px;
}

.modern-event-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.modern-event-date-box {
    text-align: center;
    min-width: 100px;
    padding-right: 20px;
}

.modern-event-date-day {
    font-size: 48px;
    font-weight: 700;
    color: #e74c3c;
    line-height: 1;
    display: block;
}

.modern-event-date-month {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 5px;
    display: block;
}

.modern-event-date-weekday {
    font-size: 14px;
    font-weight: 400;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 2px;
    display: block;
}

.modern-event-image-container {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    margin: 0 30px;
}

.modern-event-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.modern-event-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
}

.modern-event-placeholder i {
    font-size: 50px;
    color: #ccc;
}

.modern-event-content {
    flex: 1;
    padding-right: 20px;
}

.modern-event-category {
    font-size: 14px;
    font-weight: 600;
    color: #3498db;
    margin-bottom: 10px;
    text-transform: capitalize;
}

.modern-event-title {
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
    line-height: 1.3;
}

.modern-event-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.modern-event-meta-item {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #666;
}

.modern-event-meta-item i {
    margin-right: 8px;
    color: #999;
    width: 16px;
}

.modern-event-meta-item a {
    color: #666;
    text-decoration: none;
}

.modern-event-meta-item a:hover {
    color: #3498db;
    text-decoration: underline;
}

.modern-event-action {
    display: flex;
    align-items: center;
}

.modern-event-btn {
    padding: 12px 30px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.modern-event-btn:hover {
    background: #3498db;
    border-color: #3498db;
    color: #fff;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 992px) {
    .modern-event-card {
        padding: 20px 15px;
    }
    
    .modern-event-date-box {
        min-width: 80px;
        padding-right: 15px;
    }
    
    .modern-event-date-day {
        font-size: 36px;
    }
    
    .modern-event-image-container {
        width: 130px;
        height: 130px;
        margin: 0 20px;
    }
    
    .modern-event-title {
        font-size: 20px;
    }
}

@media (max-width: 768px) {
    .modern-event-card {
        flex-direction: column !important;
        text-align: center;
    }
    
    .modern-event-date-box {
        padding-right: 0;
        margin-bottom: 15px;
    }
    
    .modern-event-image-container {
        margin: 0 auto 20px;
    }
    
    .modern-event-content {
        padding-right: 0;
        margin-bottom: 20px;
    }
    
    .modern-event-meta {
        align-items: center;
    }
    
    .modern-event-action {
        justify-content: center;
    }
}
</style>

<script>
// Modern Layout Rendering Function - Event Style
function renderModernLayout(data, container) {
    data.forEach(function(org) {
        var fieldValues = buildFieldValues(org);
        
        // Parse date for display (using Created On date)
        var dateObj = org.createdOn ? new Date(org.createdOn) : new Date();
        var day = dateObj.getDate();
        var month = dateObj.toLocaleString('en-US', { month: 'short' }).toUpperCase();
        var weekday = dateObj.toLocaleString('en-US', { weekday: 'short' }).toUpperCase();
        
        // Organization image (circular)
        var orgDefaultImg = '<?php echo esc_url( ENGAGIFII_ASSETS_URL . "/images/org-list-grey.png" ); ?>';
        var orgPhoto = isValidUrl(org.imageThumbUrl)
            ? '<img src="' + org.imageThumbUrl + '" class="modern-event-card-img" alt="' + org.name + '">'
            : '<img src="' + orgDefaultImg + '" class="modern-event-card-img" alt="' + org.name + '">';
        
        // Get category (Organization Type)
        var category = fieldValues.organizationtype !== '--' ? fieldValues.organizationtype : 'Organization';
        
        // Build meta information based on configured columns
        var metaItemsHtml = '';
        var titleText = fieldValues.name || 'Organization';
        
        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            var label = getFieldLabel(colObj.displayName);
            
            // Skip name as it's used as title
            if (col === 'name') return;
            
            // Skip if no value
            if (fieldValues[col] === undefined || fieldValues[col] === '--') return;
            
            // Add icon based on field type
            var icon = '';
            if (col === 'primaryemail') {
                icon = '<i class="far fa-envelope"></i>';
            } else if (col === 'phonenumbers') {
                icon = '<i class="far fa-phone"></i>';
            } else if (col === 'locations' || col === 'location') {
                icon = '<i class="far fa-map-marker-alt"></i>';
            } else if (col === 'status') {
                icon = '<i class="far fa-check-circle"></i>';
            } else if (col === 'website') {
                icon = '<i class="far fa-globe"></i>';
            } else if (col === 'createdon') {
                icon = '<i class="far fa-calendar-plus"></i>';
            } else if (col === 'modifiedon') {
                icon = '<i class="far fa-sync-alt"></i>';
            } else if (col === 'organizationtags') {
                icon = '<i class="far fa-tags"></i>';
            } else {
                icon = '<i class="far fa-info-circle"></i>';
            }
            
            metaItemsHtml += '<div class="modern-event-meta-item">' + icon + ' ' + fieldValues[col] + '</div>';
        });
        
        var card = '<div class="col-12">' +
            '<div class="modern-event-card d-flex align-items-center">' +
            '<div class="modern-event-date-box">' +
            '<span class="modern-event-date-day">' + (day < 10 ? '0' + day : day) + '</span>' +
            '<span class="modern-event-date-month">' + month + '</span>' +
            '<span class="modern-event-date-weekday">' + weekday + '</span>' +
            '</div>' +
            '<div class="modern-event-image-container">' +
            orgPhoto +
            '</div>' +
            '<div class="modern-event-content">' +
            '<div class="modern-event-category">' + category + '</div>' +
            '<h3 class="modern-event-title">' + titleText + '</h3>' +
            '<div class="modern-event-meta">' +
            metaItemsHtml +
            '</div>' +
            '</div>' +
            '<div class="modern-event-action">' +
            '<a href="' + organizationDetailLink + '?organizationId=' + org.id + '" class="modern-event-btn">More Details</a>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        container.append(card);
    });
}
</script>
