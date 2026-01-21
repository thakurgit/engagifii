/**
 * Engagifii Admin Settings JavaScript
 */
(function($) {
    'use strict';

    // Copy to clipboard functionality for shortcodes
    window.copyToClipboard = function(element) {
        const text = element.textContent;
        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const originalHint = element.nextElementSibling;
            const originalText = originalHint.textContent;
            originalHint.textContent = "Copied!";
            originalHint.style.opacity = "1";
            
            // Reset after 2 seconds
            setTimeout(function() {
                originalHint.textContent = originalText;
                originalHint.style.opacity = "";
            }, 2000);
        }).catch(function() {
            // Fallback for older browsers
            const textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand("copy");
                const originalHint = element.nextElementSibling;
                const originalText = originalHint.textContent;
                originalHint.textContent = "Copied!";
                originalHint.style.opacity = "1";
                
                setTimeout(function() {
                    originalHint.textContent = originalText;
                    originalHint.style.opacity = "";
                }, 2000);
            } catch (err) {
                console.error("Could not copy text: ", err);
            }
            document.body.removeChild(textArea);
        });
    };

    $(document).ready(function() {
        
        // Initialize module cards
        initializeModuleCards();
        
        // Handle module toggle changes
        $('.module-checkbox').on('change', function() {
            var card = $(this).closest('.engagifii-module-card');
            if ($(this).is(':checked')) {
                card.addClass('module-enabled');
            } else {
                card.removeClass('module-enabled');
            }
            updateSelectedCount();
        });
        
        // Initialize card states
        $('.module-checkbox:checked').each(function() {
            $(this).closest('.engagifii-module-card').addClass('module-enabled');
        });
        
        // Update selected count on page load
        updateSelectedCount();
        
        // Handle select all functionality (if implemented)
        $('#select-all-modules').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.module-checkbox').prop('checked', isChecked).trigger('change');
        });
        
        // Smooth scroll to actions section after module selection
        $('.module-checkbox').on('change', function() {
            if ($('.module-checkbox:checked').length > 0) {
                setTimeout(function() {
                    var $actions = $('.engagifii-actions');
                    if ($actions.length > 0) {
                        $('html, body').animate({
                            scrollTop: $actions.offset().top - 100
                        }, 500);
                    }
                }, 300);
            }
        });
        
        // Add loading states to buttons
        $('.button-hero, #create-missing-pages').on('click', function() {
            var $button = $(this);
            var originalText = $button.text();
            
            $button.data('original-text', originalText);
            
            // Don't add loading state if form validation fails
            if (!validateForm()) {
                return;
            }
            
            $button.prop('disabled', true).addClass('loading');
        });
        
        // Form validation
        function validateForm() {
            var checkedModules = $('.module-checkbox:checked').length;
            
            if (checkedModules === 0) {
                alert('Please select at least one module to continue.');
                return false;
            }
            
            return true;
        }
        
        // Update selected modules count
        function updateSelectedCount() {
            var selectedCount = $('.module-checkbox:checked').length;
            var totalCount = $('.module-checkbox').length;
            
            // Create or update count display
            var $countDisplay = $('#selected-count');
            if ($countDisplay.length === 0) {
                $countDisplay = $('<div id="selected-count" class="selected-count"></div>');
                $('.engagifii-modules-grid').before($countDisplay);
            }
            
            if (selectedCount > 0) {
                $countDisplay.html('<strong>' + selectedCount + '</strong> of <strong>' + totalCount + '</strong> modules selected').show();
            } else {
                $countDisplay.hide();
            }
        }
        
        // Initialize module cards with staggered animation
        function initializeModuleCards() {
            $('.engagifii-module-card').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(30px)'
                });
                
                setTimeout(function(card) {
                    card.animate({
                        'opacity': '1',
                        'transform': 'translateY(0)'
                    }, 400);
                }, index * 100, $(this));
            });
        }
        
        // Add tooltips for module features
        if ($.fn.tooltip) {
            $('.module-features').tooltip({
                position: { my: "left top+15", at: "left bottom" }
            });
        }
        
        // Handle keyboard navigation
        $('.module-toggle').on('keydown', function(e) {
            if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                e.preventDefault();
                $(this).find('input').click();
            }
        });
        
        // Add search functionality (if search box exists)
        $('#module-search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            
            $('.engagifii-module-card').each(function() {
                var $card = $(this);
                var title = $card.find('h3').text().toLowerCase();
                var description = $card.find('.module-description').text().toLowerCase();
                
                if (title.indexOf(searchTerm) !== -1 || description.indexOf(searchTerm) !== -1) {
                    $card.show().removeClass('hidden-by-search');
                } else {
                    $card.hide().addClass('hidden-by-search');
                }
            });
            
            // Show message if no results
            var visibleCards = $('.engagifii-module-card:visible').length;
            $('#no-results-message').remove();
            
            if (visibleCards === 0 && searchTerm.length > 0) {
                $('.engagifii-modules-grid').append(
                    '<div id="no-results-message" class="no-results">' +
                    '<p>No modules found matching "' + searchTerm + '"</p>' +
                    '</div>'
                );
            }
        });
        
        // Handle wizard-style navigation (if implemented)
        $('.next-step').on('click', function() {
            var currentStep = $(this).data('step');
            var nextStep = currentStep + 1;
            
            // Hide current step
            $('#step-' + currentStep).fadeOut(300, function() {
                // Show next step
                $('#step-' + nextStep).fadeIn(300);
                
                // Update progress indicator
                updateProgressIndicator(nextStep);
            });
        });
        
        $('.prev-step').on('click', function() {
            var currentStep = $(this).data('step');
            var prevStep = currentStep - 1;
            
            // Hide current step
            $('#step-' + currentStep).fadeOut(300, function() {
                // Show previous step
                $('#step-' + prevStep).fadeIn(300);
                
                // Update progress indicator
                updateProgressIndicator(prevStep);
            });
        });
        
        function updateProgressIndicator(step) {
            $('.step-indicator .step').removeClass('active completed');
            
            for (var i = 1; i < step; i++) {
                $('.step-indicator .step-' + i).addClass('completed');
            }
            
            $('.step-indicator .step-' + step).addClass('active');
        }
        
        // Handle advanced options toggle
        $('#show-advanced-options').on('click', function(e) {
            e.preventDefault();
            
            var $advancedSection = $('#advanced-options');
            var $toggleText = $(this);
            
            if ($advancedSection.is(':visible')) {
                $advancedSection.slideUp(300);
                $toggleText.text('Show Advanced Options');
            } else {
                $advancedSection.slideDown(300);
                $toggleText.text('Hide Advanced Options');
            }
        });
        
        // Auto-save draft selections
        $('.module-checkbox').on('change', function() {
            var selectedModules = [];
            $('.module-checkbox:checked').each(function() {
                selectedModules.push($(this).val());
            });
            
            // Save to localStorage as draft
            localStorage.setItem('engagifii_module_draft', JSON.stringify(selectedModules));
        });
        
        // Restore draft selections on page load
        function restoreDraftSelections() {
            var draft = localStorage.getItem('engagifii_module_draft');
            if (draft) {
                try {
                    var selectedModules = JSON.parse(draft);
                    selectedModules.forEach(function(moduleKey) {
                        $('.module-checkbox[value="' + moduleKey + '"]').prop('checked', true).trigger('change');
                    });
                } catch (e) {
                    // Invalid draft data, ignore
                }
            }
        }
        
        // Clear draft after successful submission
        $(document).on('engagifii-setup-complete', function() {
            localStorage.removeItem('engagifii_module_draft');
        });
        
    });
    
    // Global functions for external access
    window.EngagifiiSettings = {
        validateForm: function() {
            return $('.module-checkbox:checked').length > 0;
        },
        
        getSelectedModules: function() {
            var modules = [];
            $('.module-checkbox:checked').each(function() {
                modules.push($(this).val());
            });
            return modules;
        },
        
        selectModule: function(moduleKey) {
            $('.module-checkbox[value="' + moduleKey + '"]').prop('checked', true).trigger('change');
        },
        
        deselectModule: function(moduleKey) {
            $('.module-checkbox[value="' + moduleKey + '"]').prop('checked', false).trigger('change');
        }
    };
    
})(jQuery);

// Add some additional CSS through JavaScript for dynamic styling
jQuery(document).ready(function($) {
    // Add dynamic styles
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .selected-count {
                text-align: center;
                padding: 15px;
                background: #e7f3ff;
                border: 1px solid #b8d4f1;
                border-radius: 4px;
                margin: 20px 0;
                font-size: 16px;
                color: #0073aa;
            }
            
            .no-results {
                grid-column: 1 / -1;
                text-align: center;
                padding: 40px;
                color: #646970;
                font-style: italic;
            }
            
            .loading {
                position: relative;
                pointer-events: none;
            }
            
            .loading::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 16px;
                height: 16px;
                border: 2px solid transparent;
                border-top: 2px solid #fff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }
        `)
        .appendTo('head');
});
