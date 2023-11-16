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
