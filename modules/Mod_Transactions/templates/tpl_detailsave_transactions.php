<style>
    /* we do the stylesheet inline, because it only applies to this page */
    .formsection-line
    {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px,1fr));
    }
    .formsection-line input[type=text]
    {
        width: 100%;
    }
</style>
<form id="detailsave" name="detailsave" method="post" action="<?php echo getURLThisScript(); ?>">

    <!-- form obligatory fields -->
    <?php echo $objController->objHidFormSubmitted->renderHTMLNode(); //able to detect if form is submitted via FormGenerator ?>
    <?php echo $objController->objHidCSRFToken->renderHTMLNode(); //able to detect Cross Site Request Forgery via FormGenerator ?>


    <!-- the details section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_details_name', 'Details'); ?></div>

        <!-- invoice type -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_transactiontype_description', 'Transaction type'); ?></div>
            <?php echo $objController->objSelTransactionsType->renderHTMLNode(); ?>
        </div>

        <!-- currency -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_currency_description', 'Currency'); ?></div>
            <?php echo $objController->objSelCurrency->renderHTMLNode(); ?>
        </div>       

        <!-- buyer -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_buyer_description', 'Buyer'); ?></div>
            <?php echo $objController->objHidBuyer->renderHTMLNode(); ?>
        </div>         
        
        <!-- purchase order number -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_purchaseorderno_description', 'Purchase order number'); ?></div>
            <?php echo $objController->objEdtPurchaseOrderNo->renderHTMLNode(); ?>
        </div>          

    </div> 


    <!-- the lines section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_lines_name', 'Lines'); ?></div>

        <div class="transactionlines-grid">
            <div class="formsection-line">
                <!-- quantity -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_quantity_description', 'Quantity'); ?></div>
                    <?php echo $objController->objEdtQuantity->renderHTMLNode(); ?>
                </div>

                <!-- description -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_description_description', 'Description'); ?></div>                    
                    <?php echo $objController->objEdtDescription->renderHTMLNode(); ?>
                </div>

                <!-- vat percentage -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_vatpercentage_description', 'VAT Percentage'); ?></div>                                        
                    <?php echo $objController->objEdtVATPercentage->renderHTMLNode(); ?>
                </div>                

                <!-- purchase price -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_purchasepriceexclvat_description', 'Purchase price (excl VAT)'); ?></div>                                        
                    <?php echo $objController->objEdtPurchasePriceExclVAT->renderHTMLNode(); ?>
                </div>   
                
                <!-- discount price -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_discountprice_description', 'Discount (excl VAT)'); ?></div>                                        
                    <?php echo $objController->objEdtDiscountPriceExclVAT->renderHTMLNode(); ?>
                </div>               
                
                <!-- unit price excluding vat -->
                <div>
                    <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_unitpriceexclvat_description', 'Unit price (excl VAT)'); ?></div>                                        
                    <?php echo $objController->objEdtPriceExclVAT->renderHTMLNode(); ?>
                </div>                   
            </div>
        </div>
    </div> 

    
    <!-- notes section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_notes_name', 'Notes'); ?></div>


        <!-- internal notes -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_internalnotes_description', 'Internal notes - only visible to you'); ?></div>
            <?php echo $objController->objTxtNotesInternal->renderHTMLNode(); ?>
        </div>       

        <!-- external notes -->
        <div class="formsection-line">
            <div class="form-description" for=""><?php echo transm($objController->getModule(), 'detailsave_transactions_field_externalnotes_description', 'External notes - shown on invoice'); ?></div>
            <?php echo $objController->objTxtNotesExternal->renderHTMLNode(); ?>
        </div>        
    </div> 



    <!-- history section -->
    <div class="formsection">
        <div class="formsection-header"><?php echo transm($objController->getModule(), 'detailsave_transactions_section_history_name', 'History'); ?></div>

    </div> 


    <!-- command panel -->
    <div class="formsection div_commandpanel">
        <?php echo $objController->objSubmit->renderHTMLNode(); ?>
        <?php echo $objController->objCancel->renderHTMLNode(); ?>
    </div> 

</form>
