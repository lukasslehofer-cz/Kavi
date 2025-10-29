<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potvrzení předplatného</title>
    <style>
        /* Reset styles */
        body, table, td, a { 
            -webkit-text-size-adjust: 100%; 
            -ms-text-size-adjust: 100%; 
        }
        table, td { 
            mso-table-lspace: 0pt; 
            mso-table-rspace: 0pt; 
        }
        img { 
            -ms-interpolation-mode: bicubic; 
            border: 0; 
            height: auto; 
            line-height: 100%; 
            outline: none; 
            text-decoration: none; 
        }
        
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .header {
            background-color: #111827;
            padding: 32px 40px;
            text-align: center;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
        }
        
        .content {
            padding: 40px;
            color: #374151;
        }
        
        .success-icon {
            width: 64px;
            height: 64px;
            background-color: #10b981;
            border-radius: 50%;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-center;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 12px 0;
            line-height: 1.2;
        }
        
        .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 32px 0;
            font-weight: 300;
        }
        
        .info-box {
            background-color: #f3f4f6;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 12px 0;
        }
        
        .info-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
            margin: 4px 0;
        }
        
        .config-box {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .config-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .config-item:last-child {
            border-bottom: none;
        }
        
        .config-label {
            color: #6b7280;
            font-weight: 300;
            font-size: 14px;
        }
        
        .config-value {
            font-weight: 600;
            color: #111827;
        }
        
        .highlight-box {
            background-color: #fef3c7;
            border-left: 4px solid: #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .button {
            display: inline-block;
            background-color: #e6305a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 15px;
            margin: 24px 0;
            text-align: center;
        }
        
        .button:hover {
            background-color: #d12a51;
        }
        
        .footer {
            background-color: #f9fafb;
            padding: 32px 40px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            margin: 8px 0;
            font-weight: 300;
        }
        
        .footer-links {
            margin: 16px 0;
        }
        
        .footer-link {
            color: #e6305a;
            text-decoration: none;
            margin: 0 8px;
        }
        
        .trust-badges {
            margin: 24px 0;
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            margin: 8px 12px;
            font-size: 13px;
            color: #059669;
        }
        
        @media only screen and (max-width: 600px) {
            .content {
                padding: 24px !important;
            }
            
            h1 {
                font-size: 24px !important;
            }
            
            .header {
                padding: 24px !important;
            }
            
            .footer {
                padding: 24px !important;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="<?php echo e(url('images/kavi-logo-black.png')); ?>" alt="Kavi Coffee" class="logo" style="filter: invert(1) brightness(2);">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <!-- Success Icon -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #10b981; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center;">
                                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#ffffff" style="stroke-width: 3;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Vítejte v Kavi Coffee! ☕</h1>
                            <p class="subtitle" style="text-align: center;">Vaše kávové předplatné bylo úspěšně aktivováno.</p>
                            
                            <!-- Subscription Number -->
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Číslo předplatného</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;"><?php echo e($subscription->subscription_number); ?></div>
                            </div>
                            
                            <!-- Subscription Configuration -->
                            <div class="config-box">
                                <h2 class="info-title">📦 Vaše kávové předplatné</h2>
                                
                                <div class="config-item">
                                    <span class="config-label">Typ kávy:</span>
                                    <span class="config-value">
                                        <?php if($subscription->configuration['type'] === 'espresso'): ?>
                                            Espresso
                                        <?php elseif($subscription->configuration['type'] === 'filter'): ?>
                                            Filter
                                        <?php else: ?>
                                            Mix (<?php echo e($subscription->configuration['mix']['espresso'] ?? 0); ?>× Espresso, <?php echo e($subscription->configuration['mix']['filter'] ?? 0); ?>× Filter)
                                        <?php endif; ?>
                                        <?php if($subscription->configuration['isDecaf'] ?? false): ?>
                                            <span style="color: #059669;"> • Decaf</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">Množství:</span>
                                    <span class="config-value"><?php echo e($subscription->configuration['amount']); ?>× balení po 250g</span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">Frekvence:</span>
                                    <span class="config-value">
                                        <?php if($subscription->frequency_months == 1): ?>
                                            Každý měsíc
                                        <?php elseif($subscription->frequency_months == 2): ?>
                                            Každé 2 měsíce
                                        <?php else: ?>
                                            Každé 3 měsíce
                                        <?php endif; ?>
                                    </span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">Cena za dodávku:</span>
                                    <span class="config-value" style="font-size: 18px; color: #e6305a;"><?php echo e(number_format($subscription->configured_price, 0, ',', ' ')); ?> Kč</span>
                                </div>
                            </div>
                            
                            <!-- Next Shipment -->
                            <div class="info-box" style="background-color: #dbeafe; border-left: 4px solid #3b82f6;">
                                <h3 class="info-title" style="color: #1e40af;">📅 První doručení</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    <strong>První kávový box vám dorazí:</strong><br>
                                    <?php echo e($subscription->next_shipment_date ? $subscription->next_shipment_date->format('j. n. Y') : 'Brzy'); ?>

                                </p>
                                <p class="info-text" style="color: #1e3a8a; margin-top: 8px;">
                                    Další doručení automaticky <?php echo e($subscription->next_billing_date ? $subscription->next_billing_date->format('j. n. Y') : 'podle frekvence'); ?>

                                </p>
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="info-box">
                                <h3 class="info-title">📦 Doručení</h3>
                                <?php if(isset($subscription->packeta_point_name)): ?>
                                <p class="info-text"><strong>Výdejní místo:</strong></p>
                                <p class="info-text"><?php echo e($subscription->packeta_point_name); ?></p>
                                <?php if(isset($subscription->packeta_point_address)): ?>
                                <p class="info-text" style="color: #6b7280;"><?php echo e($subscription->packeta_point_address); ?></p>
                                <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if($subscription->delivery_notes): ?>
                                <p class="info-text" style="margin-top: 12px;">
                                    <strong>Poznámka:</strong><br>
                                    <?php echo e($subscription->delivery_notes); ?>

                                </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Billing Info -->
                            <div class="info-box">
                                <h3 class="info-title">📋 Fakturační údaje</h3>
                                <p class="info-text"><strong><?php echo e($subscription->shipping_address['name']); ?></strong></p>
                                <p class="info-text"><?php echo e($subscription->shipping_address['billing_address']); ?></p>
                                <p class="info-text"><?php echo e($subscription->shipping_address['billing_postal_code']); ?> <?php echo e($subscription->shipping_address['billing_city']); ?></p>
                                <p class="info-text" style="margin-top: 8px;">
                                    <strong>Email:</strong> <?php echo e($subscription->shipping_address['email']); ?>

                                    <?php if(isset($subscription->shipping_address['phone'])): ?>
                                    <br><strong>Telefon:</strong> <?php echo e($subscription->shipping_address['phone']); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <!-- Subscription Status -->
                            <?php if($subscription->status === 'active'): ?>
                            <div class="info-box" style="background-color: #d1fae5; border-left: 4px solid #10b981;">
                                <h3 class="info-title" style="color: #065f46;">✓ Stav předplatného</h3>
                                <p class="info-text" style="color: #047857;">
                                    Vaše předplatné je <strong>aktivní</strong>. Další platba proběhne automaticky.
                                </p>
                            </div>
                            <?php else: ?>
                            <div class="info-box" style="background-color: #fef3c7; border-left: 4px solid #f59e0b;">
                                <h3 class="info-title" style="color: #92400e;">⏳ Stav předplatného</h3>
                                <p class="info-text" style="color: #78350f;">
                                    Vaše předplatné čeká na aktivaci. Po potvrzení platby bude automaticky aktivováno.
                                </p>
                            </div>
                            <?php endif; ?>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="<?php echo e(route('dashboard.subscription')); ?>" class="button">
                                    Spravovat předplatné
                                </a>
                            </div>
                            
                            <!-- Trust Badges -->
                            <div class="trust-badges">
                                <div class="badge">
                                    ✓ Čerstvě pražená káva
                                </div>
                                <div class="badge">
                                    ✓ Flexibilní předplatné
                                </div>
                                <div class="badge">
                                    ✓ Zrušení kdykoliv
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Své předplatné můžete kdykoliv upravit, pozastavit nebo zrušit v zákaznickém účtu. Pokud máte jakékoliv dotazy, kontaktujte nás na 
                                <a href="mailto:info@kavi.cz" style="color: #e6305a; text-decoration: none;">info@kavi.cz</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                S pozdravem,<br>
                                <strong style="color: #111827;">Tým Kavi Coffee</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">Kavi Coffee</strong><br>
                                Prémiová káva s předplatným
                            </p>
                            <div class="footer-links">
                                <a href="<?php echo e(route('home')); ?>" class="footer-link" style="color: #e6305a;">Domů</a>
                                <a href="<?php echo e(route('products.index')); ?>" class="footer-link" style="color: #e6305a;">Obchod</a>
                                <a href="<?php echo e(route('dashboard.subscription')); ?>" class="footer-link" style="color: #e6305a;">Moje předplatné</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © <?php echo e(date('Y')); ?> Kavi Coffee. Všechna práva vyhrazena.
                            </p>
                            <p class="footer-text" style="font-size: 12px;">
                                Tento e-mail byl odeslán na adresu <?php echo e($subscription->shipping_address['email']); ?><br>
                                protože jste si vytvořili kávové předplatné na našem e-shopu.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<?php /**PATH /var/www/html/resources/views/emails/subscription-confirmation.blade.php ENDPATH**/ ?>