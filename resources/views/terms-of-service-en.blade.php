@extends('layouts.app')

@section('title', 'Terms of Service - KAVI')

@section('content')

<!-- Hero Section -->
<div class="relative bg-gray-100 py-16 md:py-20 overflow-hidden">
    <!-- Subtle Organic Shapes -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
        <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full hidden md:block"></div>
    </div>
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Minimal Badge -->
            <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
                <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">T&C</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                Terms of Service
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light">
                Rules for purchasing and using services in our e-shop
            </p>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
        </svg>
    </div>
</div>

<!-- Content Section -->
<div class="relative bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 md:px-8">
        
        <!-- Intro -->
        <div class="mb-16">
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <p class="text-gray-700 font-light leading-relaxed mb-3">
                    These General Terms and Conditions (hereinafter "Terms") are issued pursuant to Section 1751 et seq. of Act No. 89/2012 Coll., the Civil Code (hereinafter "Civil Code")
                </p>
                <div class="mt-4 space-y-2">
                    <p class="text-gray-900 font-medium">Lukáš Šlehofer</p>
                    <p class="text-gray-700 font-light">ID: 66899095 | VAT ID: CZ7912150191</p>
                    <p class="text-gray-700 font-light">Registered office: Kurzova 2222/16, Prague 5, 155 00, Czech Republic</p>
                    <p class="text-gray-700 font-light">Registered in the trade register at the Prague 13 Municipal Authority</p>
                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:info@kavibox.com" class="text-primary-600 hover:text-primary-700 font-medium">info@kavibox.com</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- I. Basic Provisions -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">I</span>
                Basic Provisions
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> These Terms govern the mutual rights and obligations of the seller and a natural person who concludes a purchase contract outside their business activities as a consumer, or within their business activities (hereinafter "buyer") through the web interface located on the website available at <a href="http://kavibox.com" class="text-primary-600 hover:text-primary-700 font-medium">http://kavibox.com</a> (hereinafter "online store").
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> The provisions of these Terms are an integral part of the purchase contract. Divergent provisions in the purchase contract take precedence over the provisions of these Terms.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> These Terms and the purchase contract are concluded in English.
                    </p>
                </div>
            </div>
        </section>

        <!-- II. Information About Goods and Prices -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">II</span>
                Information About Goods and Prices
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Information about goods, including prices of individual goods and their main features, is listed for individual goods in the online store catalog. Prices of goods include value added tax, all related fees, and costs for returning goods if the goods cannot by their nature be returned by regular post. Prices of goods remain valid for as long as they are displayed in the online store. This provision does not exclude the negotiation of a purchase contract under individually agreed conditions.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> All product presentations in the online store catalog are informative in nature, and the seller is not obliged to conclude a purchase contract for these goods.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Information about packaging and delivery costs is published in the online store. This information is valid only for deliveries within the European Union.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Any discounts from the purchase price cannot be combined unless the seller and buyer agree otherwise.
                    </p>
                </div>
            </div>
        </section>

        <!-- III. Order and Conclusion of Purchase Contract -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">III</span>
                Order and Conclusion of Purchase Contract
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Costs incurred by the buyer when using remote communication means in connection with the conclusion of the purchase contract (internet connection costs, telephone call costs) are borne by the buyer. These costs do not differ from the basic rate.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> The buyer orders goods in the following ways:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>Through their customer account, if they have previously registered in the online store,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>By filling out the order form without registration.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> When placing an order, the buyer selects the goods, number of items, payment method, and delivery method.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Before submitting the order, the buyer is allowed to check and change the data entered in the order. The buyer submits the order to the seller by clicking the "Submit Order" button. The data provided in the order is considered correct by the seller. The validity of the order is conditional on filling in all mandatory data in the order form and the buyer's confirmation that they have read these Terms.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Immediately after receiving the order, the seller will send the buyer a confirmation of receipt of the order to the email address provided by the buyer when placing the order. This confirmation is considered the conclusion of the contract. The current Terms of the seller are attached to the confirmation. The purchase contract is concluded upon confirmation of the order by the seller to the buyer's email address.
                    </p>
                </div>
            </div>
        </section>

        <!-- IV. Subscription Cancellation -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IV</span>
                Subscription Cancellation
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> The subscription can be cancelled after 3 months from its start (i.e., from the first order).
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> The subscription can be cancelled through the customer account in the online store or by contacting the seller's email address.
                    </p>
                </div>
            </div>
        </section>

        <!-- V. Customer Account -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">V</span>
                Customer Account
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Based on the buyer's registration in the online store, the buyer can access their customer account. From their customer account, the buyer can order goods and make changes to an already purchased subscription. The buyer can also order goods without registration.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> When registering for a customer account and ordering goods, the buyer is obliged to provide all information correctly and truthfully. The buyer is obliged to update the information in the user account upon any change. The information provided by the buyer in the customer account and when ordering goods is considered correct by the seller.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Access to the customer account is secured by a username and password. The buyer is obliged to maintain confidentiality regarding information necessary to access their customer account. The seller is not responsible for any misuse of the customer account by third parties.
                    </p>
                </div>
            </div>
        </section>

        <!-- VI. Payment Terms and Delivery of Goods -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VI</span>
                Payment Terms and Delivery of Goods
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> The buyer can pay the price of goods and any costs associated with the delivery of goods under the purchase contract in the following ways: <strong class="font-medium text-gray-900">cashless payment by credit card</strong>
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Goods are delivered to the buyer at the pickup point specified by the buyer in the order.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">9)</strong> The seller will issue the buyer a tax document – invoice. The tax document is sent after the order is processed to the buyer's email address. Tax documents are issued regularly each month.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">10)</strong> The buyer acquires ownership of the goods upon payment of the full purchase price for the goods including delivery costs, but no earlier than upon receipt of the goods.
                    </p>
                </div>
            </div>
        </section>

        <!-- VII. Withdrawal from Contract -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VII</span>
                Withdrawal from Contract
            </h2>
            
            <div class="space-y-6">
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Unless exceptions specified in the Civil Code apply, the buyer has the right to withdraw from the purchase contract in accordance with Section 1829(1) of the Civil Code within <strong class="font-medium text-gray-900">14 days from receipt of goods</strong>, and in case the subject of the purchase contract is several types of goods or delivery of several parts, this period runs from the date of receipt of the last delivery of goods.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> To withdraw from the purchase contract, the buyer may use the withdrawal form provided by the seller. The buyer shall send the withdrawal from the purchase contract to the seller's email or delivery address specified in these Terms.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> If the buyer withdraws from the contract, the seller will return to the buyer without undue delay, no later than 14 days from the withdrawal from the contract, all funds including delivery costs that the seller received from the buyer, in the same manner.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">8)</strong> The buyer must return the goods to the seller undamaged, unworn, uncontaminated, and in original packaging. The seller is entitled to unilaterally set off the claim for compensation for damage caused to the goods against the buyer's claim for refund of the purchase price.
                    </p>
                </div>
            </div>
        </section>

        <!-- VIII. Rights from Defective Performance -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VIII</span>
                Rights from Defective Performance
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> The seller is responsible to the buyer for ensuring that the goods are free from defects upon receipt. In particular, the seller is responsible to the buyer for ensuring that at the time the buyer received the goods, the goods have the properties agreed upon by the parties, and in the absence of agreement, have such properties as described by the seller or manufacturer or expected by the buyer given the nature of the goods and based on their advertising.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> The seller has obligations from defective performance at least to the extent in which the manufacturer's obligations from defective performance apply. Otherwise, the buyer is entitled to exercise the right from a defect that occurs in consumer goods within <strong class="font-medium text-gray-900">twenty-four months from receipt</strong>.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">5)</strong> In case of a defect, the buyer may submit a complaint to the seller and request:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Replacement with new goods,
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            A reasonable discount from the purchase price,
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Withdrawal from the contract.
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">15)</strong> The seller or an authorized employee will decide on the complaint immediately, in complex cases within three business days. This period does not include a reasonable time depending on the type of product or service needed for professional assessment of the defect. Complaints including defect removal must be handled without undue delay, no later than <strong class="font-medium text-gray-900">30 days from the date of the complaint</strong>, unless the seller and buyer agree on a longer period.
                    </p>
                </div>
            </div>
        </section>

        <!-- IX. Delivery -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IX</span>
                Delivery
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> The contracting parties may deliver all written correspondence to each other by electronic mail.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> The buyer delivers correspondence to the seller to the email address specified in these Terms. The seller delivers correspondence to the buyer to the email address specified in their customer account or in the order.
                    </p>
                </div>
            </div>
        </section>

        <!-- X. Personal Data -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">X</span>
                Personal Data
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> All information that the buyer provides when cooperating with the seller is confidential and will be treated as such. Unless the buyer gives written consent to the seller, the seller will not use the buyer's data in any way other than for the performance of the contract, except for the email address to which commercial messages may be sent, as this procedure is permitted by law unless expressly refused. These messages may only concern similar or related goods and can be unsubscribed at any time by a simple method (sending a letter, email, or clicking the link in the commercial message). The email address will be kept for this purpose for 3 years from the conclusion of the last contract between the contracting parties.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> More detailed information about personal data protection can be found in our <a href="{{ localizedRoute('privacy-policy') }}" class="text-primary-600 hover:text-primary-700 font-medium">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </section>

        <!-- XI. Out-of-Court Dispute Resolution -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">XI</span>
                Out-of-Court Dispute Resolution
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> For out-of-court resolution of consumer disputes from the purchase contract, the Czech Trade Inspection Authority with registered office at Štěpánská 567/15, 120 00 Prague 2, ID: 000 20 869, website: <a href="https://adr.coi.cz/cs" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">https://adr.coi.cz/cs</a> is competent. The online dispute resolution platform located at <a href="http://ec.europa.eu/consumers/odr" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">http://ec.europa.eu/consumers/odr</a> can be used to resolve disputes between the seller and the buyer from the purchase contract.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> The seller is authorized to sell goods based on a trade license. Trade inspection is carried out by the relevant trade licensing office within its competence. The Czech Trade Inspection Authority supervises, within a defined scope, compliance with Act No. 634/1992 Coll. on Consumer Protection, among other things.
                    </p>
                </div>
            </div>
        </section>

        <!-- XII. Final Provisions -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">XII</span>
                Final Provisions
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> All agreements between the seller and the buyer are governed by the law of the Czech Republic. If the relationship established by the purchase contract contains an international element, the parties agree that the relationship is governed by Czech law. This does not affect the consumer's rights arising from generally binding legal regulations.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> All rights to the seller's website, especially copyrights to content, including page layout, photos, films, graphics, trademarks, logos, and other content and elements, belong to the seller. It is forbidden to copy, modify, or otherwise use the website or any part of it without the seller's consent.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">6)</strong> The purchase contract including the Terms is archived by the seller in electronic form and is not accessible.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">7)</strong> The seller may change or supplement the wording of the Terms. This provision does not affect rights and obligations arising during the validity of the previous version of the Terms.
                    </p>
                </div>
            </div>
        </section>

        <!-- Effective Date -->
        <div class="bg-primary-500 rounded-2xl p-8 text-center">
            <p class="text-white font-medium">
                These Terms become effective on <strong class="font-bold">January 1, 2025</strong>
            </p>
        </div>

    </div>
</div>

<!-- Contact Section -->
<div class="relative bg-gray-100 py-16">
    <div class="mx-auto max-w-4xl px-4 md:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Have Questions?
        </h2>
        <p class="text-gray-600 font-light mb-8">
            If you have any questions about our terms of service, please don't hesitate to contact us
        </p>
        <a href="mailto:info@kavibox.com" class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium px-8 py-4 rounded-full transition-all duration-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>Contact Us</span>
        </a>
    </div>
</div>

@endsection

