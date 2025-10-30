import './bootstrap';

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Quantity selector
    const quantityInputs = document.querySelectorAll('[data-quantity-input]');
    quantityInputs.forEach(input => {
        const minusBtn = input.previousElementSibling;
        const plusBtn = input.nextElementSibling;

        if (minusBtn) {
            minusBtn.addEventListener('click', () => {
                const current = parseInt(input.value);
                if (current > 1) {
                    input.value = current - 1;
                }
            });
        }

        if (plusBtn) {
            plusBtn.addEventListener('click', () => {
                const current = parseInt(input.value);
                const max = parseInt(input.max) || 999;
                if (current < max) {
                    input.value = current + 1;
                }
            });
        }
    });

    // Subscription Configurator
    initSubscriptionConfigurator();
});

function initSubscriptionConfigurator() {
    // Check if configurator exists on this page
    const configuratorContainer = document.getElementById('subscription-configurator');
    if (!configuratorContainer) {
        return; // Exit if configurator not present
    }

    console.log('Initializing subscription configurator...');

    // Availability state (loaded from API)
    let availability = {
        loaded: false,
        espresso: true,
        filter: true,
        decaf: true,
        mix: true
    };

    // Configuration state
    const config = {
        amount: null,
        cups: null,
        type: null,
        isDecaf: false,
        mix: {
            espresso: 0,
            filter: 0
        },
        frequency: null,
        frequencyText: null
    };

    // Load availability from API
    function loadAvailability() {
        fetch('/api/subscription-availability')
            .then(response => response.json())
            .then(data => {
                console.log('Availability loaded:', data);
                availability.loaded = true;
                availability.espresso = data.espresso;
                availability.filter = data.filter;
                availability.decaf = data.decaf;
                availability.mix = data.mix;
                
                // Update UI based on availability
                updateAvailabilityUI();
            })
            .catch(error => {
                console.error('Failed to load availability:', error);
                // Keep all available on error
                availability.loaded = true;
            });
    }

    // Update UI based on availability
    function updateAvailabilityUI() {
        console.log('Updating UI with availability:', availability);
        
        // Find radio buttons by their actual name/value attributes
        const espressoRadio = document.querySelector('input[name="type"][value="espresso"]');
        const filterRadio = document.querySelector('input[name="type"][value="filter"]');
        const mixRadio = document.querySelector('input[name="type"][value="mix"]');
        
        // Get parent label elements
        const espressoLabel = espressoRadio?.closest('label.group');
        const filterLabel = filterRadio?.closest('label.group');
        const mixLabel = mixRadio?.closest('label.group');
        
        // Find all decaf checkboxes (there are 3 - one for each type)
        const decafCheckboxes = document.querySelectorAll('input[name="isDecaf"]');

        // Handle Espresso availability
        if (!availability.espresso && espressoLabel) {
            console.log('Disabling espresso option');
            espressoLabel.classList.add('cursor-not-allowed');
            espressoLabel.style.pointerEvents = 'none';
            espressoLabel.style.position = 'relative';
            espressoLabel.style.backgroundColor = '#f3f4f6'; // gray-100
            
            // Add grayscale filter for content
            const contentDivs = espressoLabel.querySelectorAll('div:not(.sold-out-label)');
            contentDivs.forEach(div => {
                div.style.opacity = '0.5';
            });
            
            // Disable the radio button
            if (espressoRadio) {
                espressoRadio.disabled = true;
                if (espressoRadio.checked) {
                    espressoRadio.checked = false;
                    config.type = null;
                }
            }
            
            // Add sold out badge if not already present
            if (!espressoLabel.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = 'Tento měsíc vyprodáno';
                espressoLabel.appendChild(badge);
            }
        }

        // Handle Filter availability
        if (!availability.filter && filterLabel) {
            console.log('Disabling filter option');
            filterLabel.classList.add('cursor-not-allowed');
            filterLabel.style.pointerEvents = 'none';
            filterLabel.style.position = 'relative';
            filterLabel.style.backgroundColor = '#f3f4f6'; // gray-100
            
            // Add grayscale filter for content
            const contentDivs = filterLabel.querySelectorAll('div:not(.sold-out-label)');
            contentDivs.forEach(div => {
                div.style.opacity = '0.5';
            });
            
            // Disable the radio button
            if (filterRadio) {
                filterRadio.disabled = true;
                if (filterRadio.checked) {
                    filterRadio.checked = false;
                    config.type = null;
                }
            }
            
            // Add sold out badge if not already present
            if (!filterLabel.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = 'Tento měsíc vyprodáno';
                filterLabel.appendChild(badge);
            }
        }

        // Handle Mix availability
        if (!availability.mix && mixLabel) {
            console.log('Disabling mix option');
            mixLabel.classList.add('cursor-not-allowed');
            mixLabel.style.pointerEvents = 'none';
            mixLabel.style.position = 'relative';
            mixLabel.style.backgroundColor = '#f3f4f6'; // gray-100
            
            // Add grayscale filter for content
            const contentDivs = mixLabel.querySelectorAll('div:not(.sold-out-label)');
            contentDivs.forEach(div => {
                div.style.opacity = '0.5';
            });
            
            // Disable the radio button
            if (mixRadio) {
                mixRadio.disabled = true;
                if (mixRadio.checked) {
                    mixRadio.checked = false;
                    config.type = null;
                }
            }
            
            // Add sold out badge if not already present
            if (!mixLabel.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = 'Tento měsíc vyprodáno';
                mixLabel.appendChild(badge);
            }
        }

        // Handle Decaf availability - disable ALL decaf checkboxes
        if (!availability.decaf && decafCheckboxes.length > 0) {
            console.log('Disabling decaf options, found checkboxes:', decafCheckboxes.length);
            decafCheckboxes.forEach((checkbox) => {
                // Find the parent label container for this checkbox
                const decafContainer = checkbox.closest('label');
                
                if (decafContainer) {
                    decafContainer.classList.add('opacity-50', 'cursor-not-allowed');
                    decafContainer.style.pointerEvents = 'none';
                }
                
                // Disable and uncheck the checkbox
                checkbox.disabled = true;
                if (checkbox.checked) {
                    checkbox.checked = false;
                    config.isDecaf = false;
                }
                
                // Add note if not already present (only once, after the parent type options container)
                const typeOptionsContainer = checkbox.closest('.space-y-3');
                if (typeOptionsContainer && !typeOptionsContainer.querySelector('.decaf-unavailable-note')) {
                    const note = document.createElement('div');
                    note.className = 'decaf-unavailable-note mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700';
                    note.innerHTML = '<strong>Upozornění:</strong> Decaf káva tento měsíc není k dispozici';
                    typeOptionsContainer.appendChild(note);
                }
            });
        }
    }

    // Load availability on init
    loadAvailability();

    // Load pricing from data attribute
    const configuratorElement = document.getElementById('subscription-configurator');
    let PRICING = { '2': 500, '3': 720, '4': 920 }; // Default pricing
    
    if (configuratorElement && configuratorElement.dataset.pricing) {
        try {
            PRICING = JSON.parse(configuratorElement.dataset.pricing);
            console.log('Loaded pricing:', PRICING);
        } catch (e) {
            console.error('Failed to parse pricing data:', e);
        }
    }

    // Get button references at the start
    const configureBtn = document.getElementById('configure-subscription');
    const nextToStep3Btn = document.getElementById('next-to-step-3');

    console.log('Found configure button:', configureBtn);
    console.log('Found next button:', nextToStep3Btn);

    // Step navigation
    function goToStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.config-step').forEach(step => {
            step.classList.remove('active');
        });

        // Show target step
        const targetStep = document.getElementById(`step-${stepNumber}`);
        if (targetStep) {
            targetStep.classList.add('active');
        }

        // Update step indicators
        document.querySelectorAll('.step-indicator').forEach(indicator => {
            const indicatorStep = parseInt(indicator.dataset.step);
            indicator.classList.remove('active', 'completed');
            
            if (indicatorStep === stepNumber) {
                indicator.classList.add('active');
            } else if (indicatorStep < stepNumber) {
                indicator.classList.add('completed');
            }
        });

        // Update lines
        document.querySelectorAll('.step-line').forEach((line, index) => {
            if (index < stepNumber - 1) {
                line.classList.add('bg-primary-500');
                line.classList.remove('bg-bluegray-200');
            } else {
                line.classList.remove('bg-primary-500');
                line.classList.add('bg-bluegray-200');
            }
        });

        // Scroll to top of section
        document.querySelector('.section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Step 1: Amount Selection
    const amountOptions = document.querySelectorAll('.coffee-amount-option');
    amountOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all
            amountOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked
            this.classList.add('selected');
            
            // Save configuration
            config.amount = parseInt(this.dataset.amount);
            config.cups = this.dataset.cups;
            
            // Auto advance to step 2 after short delay
            setTimeout(() => {
                goToStep(2);
            }, 300);
        });
    });

    // Step 2: Coffee Type Selection
    const typeOptions = document.querySelectorAll('.coffee-type-option');
    const mixOptions = document.getElementById('mix-options');
    const decafOption = document.getElementById('decaf-option');
    const decafCheckbox = document.getElementById('decaf-checkbox');
    const decafSliderContainer = document.getElementById('decaf-slider-container');
    const singleDecafSlider = document.getElementById('single-decaf-slider');

    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all
            typeOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked
            this.classList.add('selected');
            
            // Save configuration
            config.type = this.dataset.type;
            
            // Reset decaf state
            config.isDecaf = false;
            config.mix = { espresso: 0, filter: 0 };
            if (decafCheckbox) decafCheckbox.checked = false;
            if (decafSliderContainer) decafSliderContainer.classList.add('hidden');
            
            // Show/hide appropriate options
            if (config.type === 'mix') {
                mixOptions.classList.remove('hidden');
                decafOption.classList.add('hidden');
                // Initialize sliders max value
                updateSliderMaxValues();
                updateMixDisplay();
                nextToStep3Btn.disabled = true;
            } else if (config.type === 'espresso' || config.type === 'filter') {
                decafOption.classList.remove('hidden');
                mixOptions.classList.add('hidden');
                nextToStep3Btn.disabled = false;
                
                // Update labels and icons for single type
                updateSingleTypeLabels();
            }
        });
    });

    // Update labels for single type (espresso or filter)
    function updateSingleTypeLabels() {
        const isEspresso = config.type === 'espresso';
        const normalIcon = isEspresso ? '🎯' : '💧';
        const normalLabel = isEspresso ? 'Espresso' : 'Filter';
        
        const elements = {
            normalIconTop: document.getElementById('single-normal-icon-top'),
            normalLabelTop: document.getElementById('single-normal-label-top'),
            normalLabel: document.getElementById('single-normal-label'),
            decafLabel: document.getElementById('single-decaf-label')
        };
        
        if (elements.normalIconTop) elements.normalIconTop.textContent = normalIcon;
        if (elements.normalLabelTop) elements.normalLabelTop.textContent = normalLabel;
        if (elements.normalLabel) elements.normalLabel.textContent = normalLabel;
        if (elements.decafLabel) elements.decafLabel.textContent = `${normalLabel} Decaf`;
        
        // Initialize slider
        if (singleDecafSlider) {
            singleDecafSlider.max = config.amount || 4;
            singleDecafSlider.value = 0;
            updateSingleTypeDisplay();
        }
    }

    // Single type decaf slider (always visible, no checkbox needed)
    if (singleDecafSlider) {
        singleDecafSlider.addEventListener('input', updateSingleTypeDisplay);
    }

    function updateSingleTypeDisplay() {
        const decafCount = parseInt(singleDecafSlider?.value || 0);
        const totalRequired = config.amount || 0;
        const normalCount = totalRequired - decafCount;
        
        // Update display
        const decafCountEl = document.getElementById('single-decaf-count');
        const normalCountEl = document.getElementById('single-normal-count');
        
        if (decafCountEl) decafCountEl.textContent = decafCount;
        if (normalCountEl) normalCountEl.textContent = normalCount;
        
        // Update config - mark as decaf if any decaf selected
        config.isDecaf = decafCount > 0;
        
        // Store in config - always use the main type fields
        // The +100 CZK decaf surcharge is handled on the backend
        if (config.type === 'espresso') {
            config.mix.espresso = normalCount;
        } else if (config.type === 'filter') {
            config.mix.filter = normalCount;
        }
    }

    // Mix sliders
    const espressoSlider = document.getElementById('espresso-slider');
    const filterSlider = document.getElementById('filter-slider');

    function updateSliderMaxValues() {
        const maxAmount = config.amount || 4;
        if (espressoSlider) espressoSlider.max = maxAmount;
        if (filterSlider) filterSlider.max = maxAmount;
        const reqBagsDisplay = document.getElementById('required-bags-display');
        if (reqBagsDisplay) reqBagsDisplay.textContent = maxAmount;
    }

    function updateMixDisplay() {
        config.mix.espresso = parseInt(espressoSlider?.value || 0);
        config.mix.filter = parseInt(filterSlider?.value || 0);

        const total = config.mix.espresso + config.mix.filter;

        // Update counts
        const espressoCount = document.getElementById('espresso-count');
        const filterCount = document.getElementById('filter-count');
        const totalBags = document.getElementById('total-bags');
        
        if (espressoCount) espressoCount.textContent = config.mix.espresso;
        if (filterCount) filterCount.textContent = config.mix.filter;
        if (totalBags) totalBags.textContent = total;

        // Update progress bar
        const progressBar = document.getElementById('mix-progress-bar');
        const mixStatus = document.getElementById('mix-status');
        const required = config.amount || 0;
        const percentage = required > 0 ? (total / required) * 100 : 0;
        
        if (progressBar) {
            progressBar.style.width = `${Math.min(percentage, 100)}%`;
        }

        // Update status message
        if (mixStatus) {
            const remaining = required - total;
            if (total === 0) {
                mixStatus.textContent = '👆 Začněte výběrem káv pomocí posuvníků';
                mixStatus.className = 'text-center mt-3 font-semibold text-dark-600';
            } else if (remaining > 0) {
                mixStatus.textContent = `Ještě musíte vybrat ${remaining} ${remaining === 1 ? 'balení' : remaining < 5 ? 'balení' : 'balení'}`;
                mixStatus.className = 'text-center mt-3 font-semibold text-primary-500 animate-pulse';
            } else if (remaining < 0) {
                mixStatus.textContent = `⚠️ Máte vybráno o ${Math.abs(remaining)} ${Math.abs(remaining) === 1 ? 'balení' : 'balení'} více!`;
                mixStatus.className = 'text-center mt-3 font-semibold text-red-600';
            } else {
                mixStatus.textContent = '✓ Perfektní! Můžete pokračovat';
                mixStatus.className = 'text-center mt-3 font-semibold text-green-600';
            }
        }

        // Validate
        if (total === required && total > 0) {
            nextToStep3Btn.disabled = false;
        } else {
            nextToStep3Btn.disabled = true;
        }
    }

    if (espressoSlider) {
        espressoSlider.addEventListener('input', updateMixDisplay);
    }
    if (filterSlider) {
        filterSlider.addEventListener('input', updateMixDisplay);
    }

    // Step navigation buttons
    document.getElementById('back-to-step-1')?.addEventListener('click', () => goToStep(1));
    
    nextToStep3Btn?.addEventListener('click', () => {
        goToStep(3);
        updateSummary();
    });

    document.getElementById('back-to-step-2')?.addEventListener('click', () => goToStep(2));

    // Step 3: Frequency Selection
    const frequencyOptions = document.querySelectorAll('.frequency-option');

    frequencyOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all
            frequencyOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked
            this.classList.add('selected');
            
            // Save configuration
            config.frequency = parseInt(this.dataset.frequency);
            config.frequencyText = this.dataset.frequencyText;
            
            console.log('Frequency selected:', config.frequency, config.frequencyText);
            
            // Enable configure button
            if (configureBtn) {
                configureBtn.disabled = false;
                configureBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                console.log('Configure button enabled!');
            } else {
                console.error('Configure button not found when trying to enable!');
            }
            
            // Update summary
            updateSummary();
        });
    });

    // Update summary
    function updateSummary() {
        // Amount
        document.getElementById('summary-amount').textContent = 
            `${config.amount} balení (${config.cups} šálky denně)`;

        // Type
        let typeText = '';
        const summaryMix = document.getElementById('summary-mix');
        const summaryMixDetails = document.getElementById('summary-mix-details');
        
        if (config.type === 'espresso') {
            typeText = config.isDecaf ? 'Espresso (vč. 1× decaf)' : 'Espresso';
            summaryMix.classList.add('hidden');
        } else if (config.type === 'filter') {
            typeText = config.isDecaf ? 'Filtr (vč. 1× decaf)' : 'Filtr';
            summaryMix.classList.add('hidden');
        } else if (config.type === 'mix') {
            typeText = config.isDecaf ? 'Kombinace (vč. 1× decaf)' : 'Kombinace';
            summaryMix.classList.remove('hidden');
            
            const mixParts = [];
            if (config.mix.espresso > 0) mixParts.push(`${config.mix.espresso}× Espresso`);
            if (config.mix.filter > 0) mixParts.push(`${config.mix.filter}× Filtr`);
            
            summaryMixDetails.innerHTML = mixParts.map(part => 
                `<div class="flex items-center py-1"><span class="text-primary-500 mr-2">•</span>${part}</div>`
            ).join('');
        }
        
        document.getElementById('summary-type').textContent = typeText;

        // Frequency
        if (config.frequencyText) {
            document.getElementById('summary-frequency').textContent = config.frequencyText;
        }

        // Price
        if (config.amount) {
            const totalPrice = PRICING[config.amount.toString()] || 0;
            document.getElementById('summary-price').textContent = 
                `${totalPrice.toLocaleString('cs-CZ')} Kč`;
        }
    }

    // Configure subscription button
    console.log('Setting up configure button event listener. Button:', configureBtn);
    
    if (configureBtn) {
        configureBtn.addEventListener('click', function(e) {
            console.log('=== Configure button clicked! ===');
            console.log('Button element:', this);
            console.log('Button disabled:', this.disabled);
            console.log('Current config:', JSON.stringify(config, null, 2));
            
            // Create form and submit to backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/predplatne/konfigurator/checkout';
            
            console.log('Form created:', form);
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }
        
        // Add configuration data as hidden inputs
        Object.keys(config).forEach(key => {
            if (key === 'mix' && typeof config[key] === 'object') {
                // Handle mix object separately
                Object.keys(config.mix).forEach(mixKey => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `mix[${mixKey}]`;
                    input.value = config.mix[mixKey];
                    form.appendChild(input);
                    console.log(`Added mix field: mix[${mixKey}] = ${config.mix[mixKey]}`);
                });
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                // Convert boolean to string for Laravel
                if (typeof config[key] === 'boolean') {
                    input.value = config[key] ? '1' : '0';
                } else {
                    input.value = config[key];
                }
                form.appendChild(input);
                console.log(`Added field: ${key} = ${input.value}`);
            }
        });
        
            // Append form to body and submit
            console.log('Appending form to body...');
            document.body.appendChild(form);
            console.log('Form appended. Submitting...');
            form.submit();
            console.log('Form submitted!');
        });
    } else {
        console.error('❌ Configure button not found! Cannot set up event listener.');
    }
}


