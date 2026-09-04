<?php
/**
 * Minimal Card Layout Template — Figma "Card View Option 3"
 */
defined('ABSPATH') || exit;
?>

<style>
/* ── Minimal card shell ─────────────────────────────────────── */
.org-card-minimal {
    border: 1px solid #ddd !important;
    border-radius: 10px !important;
    overflow: hidden;
    background: #fff !important;
    box-shadow: none !important;
    transition: box-shadow 0.25s ease;
}
.org-card-minimal:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.10) !important;
}

/* ── Single white body: pt-15 px-15 pb-25, flex-col gap-10 ─── */
.mnl-body {
    padding: 15px 15px 25px !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 10px !important;
}

/* ── Top row: logo box + info, gap-15, items-center ────────── */
.mnl-top {
    display: flex !important;
    align-items: center !important;
    gap: 15px !important;
}

/* ── Logo box: 88×75, radius-4, inset shadow ────────────────── */
.mnl-logo-box {
    width: 88px !important;
    height: 75px !important;
    min-width: 88px !important;
    border-radius: 4px !important;
    background: #fff !important;
    box-shadow: inset 0 0 8.8px 0 rgba(0,0,0,0.08) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    overflow: hidden !important;
}
.mnl-logo-box img {
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: contain !important;
}

/* ── Info column: flex-col gap-4 ───────────────────────────── */
.mnl-info {
    flex: 1 !important;
    min-width: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 4px !important;
}

/* ── Org name: 16px semibold #333 ──────────────────────────── */
.mnl-name {
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #333 !important;
    line-height: normal !important;
    margin: 0 !important;
}

/* ── Meta lines wrapper: flex-col gap-4 ────────────────────── */
.mnl-meta-wrap {
    display: flex !important;
    flex-direction: column !important;
    gap: 4px !important;
    line-height: 0 !important; /* matches Figma leading-[0] on parent */
}

/* ── Each email/phone line: 14px ───────────────────────────── */
.mnl-meta-line {
    font-size: 14px !important;
    color: #333 !important;
    line-height: normal !important;
    margin: 0 !important;
}
.mnl-meta-label {
    font-weight: 500 !important;
    color: #777 !important;
}

/* ── Divider ────────────────────────────────────────────────── */
.mnl-divider {
    border: none !important;
    border-top: 1px solid #ddd !important;
    margin: 0 !important;
}

/* ── Fields: two columns flex, gap-4, 12px, leading-0 parent ─ */
.mnl-fields {
    display: flex !important;
    gap: 4px !important;
    font-size: 12px !important;
    line-height: 0 !important; /* Figma: leading-[0] on parent */
}
.mnl-labels {
    flex: 1 1 0 !important;
    min-width: 0 !important;
    color: #777 !important;
    font-weight: 500 !important;
}
.mnl-values {
    flex: 1 1 0 !important;
    min-width: 0 !important;
    color: #333 !important;
    text-align: right !important;
}

/* ── Each field p: line-height 20px, mb-0 ──────────────────── */
.mnl-labels p,
.mnl-values p {
    line-height: 20px !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
}

/* ── Links ──────────────────────────────────────────────────── */
.mnl-values a,
.mnl-labels a {
    color: #3b4dd8 !important;
    text-decoration: none !important;
}
.mnl-values a:hover { text-decoration: underline !important; }
</style>

<script>
function renderMinimalLayout(data, container) {
    var ctx = window.engagifiiGetOrgCardContext ? window.engagifiiGetOrgCardContext() : {};
    var helpers = window.engagifiiOrgCardHelpersFromCtx ? window.engagifiiOrgCardHelpersFromCtx(ctx) : {};
    var buildFieldValues = helpers.buildFieldValues;
    var isValidUrl = helpers.isValidUrl;
    var organizationGridCols = ctx.organizationGridCols || [];
    var organizationDetailLink = ctx.organizationDetailLink || '';
    var entityType = ctx.cardEntityType || 'organization';

    data.forEach(function(org) {
        var fieldValues = buildFieldValues(org);
        var orgDefaultImg = '<?php echo esc_url( ENGAGIFII_ASSETS_URL . "/images/org-list-grey.png" ); ?>';
        var logoHtml;
        if (entityType === 'person' && !isValidUrl(org.imageThumbUrl)) {
            logoHtml = '<div class="mnl-logo-box"><i class="fa fa-user-circle text-secondary" style="font-size:48px;"></i></div>';
        } else {
            var logoSrc = isValidUrl(org.imageThumbUrl) ? org.imageThumbUrl : orgDefaultImg;
            logoHtml = '<div class="mnl-logo-box">'
                + '<img src="' + logoSrc + '" alt="' + (org.name || '') + '">'
                + '</div>';
        }

        // ── Logo box ──────────────────────────────────────────
        // logoHtml built above

        // ── Email + Phone meta lines ──────────────────────────
        var metaMap = ctx.metaContactFields || [
            { col: 'primaryemail', label: 'Email' },
            { col: 'phonenumbers', label: 'Phone' }
        ];
        // Inline style applied to every p to guarantee Bootstrap cannot override
        var pStyle = 'style="margin:0;padding:0;line-height:20px;font-size:12px;"';
        var pNameStyle = 'style="margin:0;padding:0;line-height:normal;font-size:16px;font-weight:600;color:#333;"';
        var pMetaStyle = 'style="margin:0;padding:0;line-height:normal;font-size:14px;color:#333;"';

        var metaHtml = '';
        metaMap.forEach(function(item) {
            var val = fieldValues[item.col];
            if (val && val !== '--') {
                metaHtml += '<p ' + pMetaStyle + '>'
                    + '<span class="mnl-meta-label">' + item.label + ': </span>'
                    + val + '</p>';
            }
        });

        // ── Top row (logo + info) ─────────────────────────────
        var topHtml = '<div class="mnl-top">'
            + logoHtml
            + '<div class="mnl-info">'
            + '<p ' + pNameStyle + '>' + (fieldValues.name || '') + '</p>'
            + (metaHtml ? '<div class="mnl-meta-wrap">' + metaHtml + '</div>' : '')
            + '</div>'
            + '</div>';

        // ── Two-column field rows ─────────────────────────────
        var skipCols = ctx.minimalSkipCols || ['name', 'primaryemail', 'phonenumbers'];
        var labelHtml = '';
        var valueHtml = '';
        var labelMap = {
            'Locations': 'Location',
            'Created On': 'Added',
            'Modified On': 'Last Updated',
            'Total Members': 'Members'
        };

        organizationGridCols.forEach(function(colObj) {
            var col = colObj.colClass;
            if (skipCols.indexOf(col) !== -1) return;
            var label = labelMap[colObj.displayName] || colObj.displayName;
            if (fieldValues[col] && fieldValues[col] !== '--') {
                labelHtml += '<p ' + pStyle + '>' + label + ':</p>';
                valueHtml  += '<p ' + pStyle + '>' + fieldValues[col] + '</p>';
            }
        });

        var fieldsHtml = labelHtml
            ? '<div class="mnl-fields">'
                + '<div class="mnl-labels">' + labelHtml + '</div>'
                + '<div class="mnl-values">' + valueHtml + '</div>'
                + '</div>'
            : '';

        var detailLinkHtml = (ctx.showDetailLink !== false && organizationDetailLink)
            ? '<p style="margin:8px 0 0;padding:0;">'
                + '<a href="' + organizationDetailLink + '?organizationId=' + org.id + '" class="btn btn-link btn-sm pl-0">View Detail</a>'
                + '</p>'
            : '';

        var card = '<div class="col-md-6 mb-4">'
            + '<div class="org-card-minimal">'
            + '<div class="mnl-body">'
            + topHtml
            + '<hr class="mnl-divider">'
            + fieldsHtml
            + detailLinkHtml
            + '</div></div></div>';

        container.append(card);
    });
}
</script>